<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\File;
use App\Models\User;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store');
        $this->middleware('auth:sanctum')->only('update');
        $this->middleware('auth:sanctum')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'success' => true,
            'data' => $posts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar fitxer
        $validatedData = $request->validate([
            'upload' => 'required|mimes:gif,jpeg,jpg,png|max:2048'
        ]);

        // Obtenir dades del fitxer
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        $postBody = $request->get('body'); 
        $postLatitude = $request->get('latitude');
        $postLongitude = $request->get('longitude'); 
        $visibility_id = $request->get('visibility_id');
        \Log::debug("Storing file '{$fileName}' ($fileSize)...");
 
        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $fileName;
        $filePath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Filename
            'public'        // Disk
        );
      
        if (\Storage::disk('public')->exists($filePath)) {
            \Log::debug("Local storage OK");
            $fullPath = \Storage::disk('public')->path($filePath);
            \Log::debug("File saved at {$fullPath}");

            // Desar dades a BD
            $file = File::create([
                'filepath' => $filePath,
                'filesize' => $fileSize,
            ]);
            $post = Post::create([
                'body' => $postBody,
                'latitude' => $postLatitude,
                'longitude' => $postLongitude,
                'file_id' => $file->id,
                'author_id' => auth()->user()->id,
                'visibility_id' => $visibility_id,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return response()->json([
                'success' => true,
                'data'    => $post
            ], 201);
        } else {
            \Log::debug("Local storage FAILS");
            return response()->json([
                'success'  => false,
                'message' => 'Error uploading file'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if($post == null)
        {
            return response()->json([
                'success'  => false,
                'message' => 'Error post not found'
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'data'    => $post
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if($post)
        {
            $file = File::find($post->file_id);
            // Validar fitxer
            $validatedData = $request->validate([
                'upload' => 'required|mimes:gif,jpeg,jpg,png|max:2048'
            ]);

            // Obtenir dades del fitxer
            $upload = $request->file('upload');
            $fileName = $upload->getClientOriginalName();
            $fileSize = $upload->getSize();
            $postBody = $request->get('body'); 
            $postLatitude = $request->get('latitude');
            $postLongitude = $request->get('longitude'); 
            $visibility_id = $request->get('visibility_id');
            \Log::debug("Storing file '{$fileName}' ($fileSize)...");
    
            // Pujar fitxer al disc dur
            $uploadName = time() . '_' . $fileName;
            $filePath = $upload->storeAs(
                'uploads',      // Path
                $uploadName ,   // Filename
                'public'        // Disk
            );
            if(\Storage::disk('public')->exists($filePath)){
                \Log::debug("Local storage OK");
                $fullPath = \Storage::disk('public')->path($filePath);
                \Log::debug("File saved at {$fullPath}");
                // Desar dades a BD
                $file->filepath = $filePath;
                $file->filesize = $fileSize;
                $file->save();

                $post->body = $postBody;
                $post->latitude = $postLatitude;
                $post->longitude = $postLongitude;
                $post->visibility_id = $visibility_id;
                $post->save();
                \Log::debug("DB storage OK");
                return response()->json([
                    'success' => true,
                    'data'    => $post
                ], 201);
            } else {
                \Log::debug("Local storage FAILS");
                return response()->json([
                    'success'  => false,
                    'message' => 'Error uploading post'
                ], 500);
            }
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error post not found'
            ], 404);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        if($post == null)
        {
            return response()->json([
                'success'  => false,
                'message' => 'Error post not found'
            ], 404);
        }else{
            $file = File::find($post->file_id);
            $post->delete();
            return response()->json([
                'success' => true,
                'data'    => $post
            ], 200);
        }

        if ($file == null) {
            \Log::debug(" Alredy Exist");
            return response()->json([
                'success'  => false,
                'message' => 'Error post exist'
            ], 404);
        }else{
            \Storage::disk('public')->delete($file->filepath);
            $file->delete();
            \Log::debug("Post Delete");
            return response()->json([
                'success' => true,
                'data'    => $post
            ], 200);
        }  
    }

    public function like($id)
    {
        $post=Post::find($id);
        if (Like::where([
                ['user_id', "=" , auth()->user()->id],
                ['post_id', "=" ,$id],
            ])->exists()) {
            return response()->json([
                'success'  => false,
                'message' => 'The post is already like'
            ], 500);
        }else{
            $like = Like::create([
                'user_id' => auth()->user()->id,
                'post_id' => $post->id,
            ]);
            return response()->json([
                'success' => true,
                'data'    => $like
            ], 200);
        }        
    }

    public function unlike($id)
    {
        $post=Post::find($id);
        if (Like::where([['user_id', "=" ,auth()->user()->id],['post_id', "=" ,$post->id],])->exists()) {
            
            $like = Like::where([
                ['user_id', "=" ,auth()->user()->id],
                ['post_id', "=" ,$id],
            ]);
            $like->first();
    
            $like->delete();

            return response()->json([
                'success' => true,
                'data'    => $post
            ], 200);
        }else{
            return response()->json([
                'success'  => false,
                'message' => 'The post is not like'
            ], 500);
            
        }  
        
    }
}
