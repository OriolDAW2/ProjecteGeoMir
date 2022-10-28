<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
            'title' => 'required',
            'message' => 'required',
            'upload' => 'required|mimes:gif,jpeg,jpg,png,|max:1024'
        ]);
    
        // Obtenir dades del Post
        $upload = $request->file('upload');
        $postTitle = $request->get('title');
        $postMessage = $request->get('message');
        $postName = $upload->getClientOriginalName();
        $postSize = $upload->getSize();
        \Log::debug("Storing post '{$postName}' ($postSize)...");

        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $postName;
        $postPath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Postname
            'public'        // Disk
        ); 
    
        if (\Storage::disk('public')->exists($postPath)) {
            \Log::debug("Local storage OK");
            $fullPath = \Storage::disk('public')->path($postPath);
            \Log::debug("Post saved at {$fullPath}");
            // Desar dades a BD
            $post = Post::create([
                'title' => $postTitle,
                'message' => $postMessage,
                'postpath' => $postPath,
                'postsize' => $postSize,
            ]);
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
                ->with('success', 'Post successfully saved');
        } else {
            \Log::debug("Local storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
                ->with('error', 'ERROR uploading Post');
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
        return view("posts.show", ["post" => $post])
        ->with('success', 'Post Exist');
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
            'title' => 'required|max:20 characters',
            'message' => 'required|max:255 characters',
            'upload' => 'required|mimes:gif,jpeg,jpg,png,|max:1024'
        ]);
    
        // Obtenir dades del Post
        $upload = $request->file('upload');
        $postTitle = $request->get('title');
        $postMessage = $request->get('message');
        $postName = $upload->getClientOriginalName();
        $postSize = $upload->getSize();
        \Log::debug("Storing post '{$postName}' ($postSize)...");

        // Pujar fitxer al disc dur
        $uploadName = time() . '_' . $postName;
        $postPath = $upload->storeAs(
            'uploads',      // Path
            $uploadName ,   // Postname
            'public'        // Disk
        ); 
    
        if (\Storage::disk('public')->exists($postPath)) {
            \Log::debug("Local storage OK");
            $fullPath = \Storage::disk('public')->path($postPath);
            \Log::debug("Post saved at {$fullPath}");
            // Desar dades a BD
            $post->title = $postTitle;
            $post->message = $postMessage;
            $post->postPath = $postPath;
            $post->postsize = $postSize;
            $post->save();
            \Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
                ->with('success', 'Post successfully saved');
        } else {
            \Log::debug("Local storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
                ->with('error', 'ERROR uploading Post');
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
        \Storage::disk('public')->delete($post->postpath);
        $post->delete();
        if (\Storage::disk('public')->exists($post->postpath)) {
            \Log::debug("Post Alredy Exist");
            return redirect()->route('posts.show', $post)
            ->with('error', 'ERROR Post alredy exist');
        }else{
            \Log::debug("Post Delete");
            return redirect()->route("posts.index")
            ->with('success', 'Post Deleted');
        }
    }
}
