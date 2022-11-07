<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("posts.index", [
            "posts" => Post::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar Post
        $validatedData = $request->validate([
            'body' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Obtenir dades del Post
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        $postBody = $request->get('body'); 
        $postLatitude = $request->get('latitude');
        $postLongitude = $request->get('longitude'); 
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
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
            ->with('success', 'Post successfully saved');
        } else {
            \Log::debug("Local storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
            ->with('error', 'ERROR uploading post');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view("posts.show", [
            "post" => $post,
            "file" => $post->file(),
            "user" => $post->user()
        ])->with('success', 'Post Exist');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view("posts.edit", [
            "post" => $post
        ]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // Validar Post
        $validatedData = $request->validate([
            'body' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $file = File::find($post->file_id);

        // Obtenir dades del Post
        $upload = $request->file('upload');
        $fileName = $upload->getClientOriginalName();
        $fileSize = $upload->getSize();
        $postBody = $request->get('body'); 
        $postLatitude = $request->get('latitude');
        $postLongitude = $request->get('longitude'); 
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
            $file->filepath = $filePath;
            $file->filesize = $fileSize;
            $file->save();
            $post->body = $postBody;
            $post->latitude = $postLatitude;
            $post->longitude = $postLongitude;
            $post->save();
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
            ->with('success', 'Post successfully updated');
        } else {
            \Log::debug("Local storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
            ->with('error', 'ERROR uploading post');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Eliminar el Post i el File
        $file = File::find($post->file_id);
        \Storage::disk('public')->delete($post->id);
        $post->delete();
        \Storage::disk('public')->delete($file->filepath);
        $file->delete();
        
        if (\Storage::disk('public')->exists($file->filepath)) {
            \Log::debug("Post Alredy Exist");
            return redirect()->route('posts.show', $post)
            ->with('error', 'ERROR post alredy exist');
        }else{
            \Log::debug("Post Delete");
            return redirect()->route("posts.index")
            ->with('success', 'Post Deleted');
        }
    }
}
