@extends('layouts.app')
 
@section('content')
<style>
    .edit{
        background-color: orange; 
        margin-right: 5px; 
        margin-left: 5px;
        border-color: orange;
    }
    .edit:hover{
        background-color: #D4AC0D;
        border-color: #D4AC0D;
    }
    .delete{
        background-color: red; 
        border-color: red;
    }
    .delete:hover{
        background-color: #E74C3C;
        border-color: #E74C3C;
    }
    .like{
        background-color: green; 
        margin-right: 5px; 
        margin-left: 5px;
        border-color: green;
        margin-top: 10px;
    }
    .like:hover{
        background-color: #1E894D;
        border-color: #1E894D;
    }
    .unlike{
        background-color: red; 
        margin-right: 5px; 
        margin-left: 5px;
        border-color: red;
        margin-top: 10px;
    }
    .unlike:hover{
        background-color: #E74C3C;
        border-color: #E74C3C;
    }
</style>
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{ __('Post') }} {{ $post->id }}</div>
               <div class="card-body">
                   <table class="table">
                        <thead>
                            <tr>
                                <td scope="col"><h5>Message: {{ $post->body }}</td>
                            </tr>
                            <tr>
                                <td scope="col"><h5>Latitude: {{ $post->latitude }}</td>
                            </tr>
                            <tr>
                                <td scope="col"><h5>Longitude: {{ $post->longitude }}</td>
                            </tr>
                        </thead>
                   </table>
                <img class="img-fluid" src="{{ asset("storage/{$post->file->filepath}") }}" />
                </div>
                <div class="card-footer">
                <form method="post" action="{{ route('posts.destroy', $post) }}" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('posts.index') }}" role="button">See all Posts</a>
                    <a class="btn btn-primary edit" href="{{ route('posts.edit', $post) }}" role="button">Edit</a>
                    <button type="submit" class="btn btn-primary delete">Delete</button>
                </form>
                @if($post->isLike())
                <form method="post" action="{{ route('posts.like', $post) }}" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-primary like" role="button">Like</button>
                    <p style="float: right">Created: {{ $post->created_at }}
                </form>
                @else
                <form method="post" action="{{ route('posts.unlike', $post) }}" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-primary unlike" role="button">Unlike</button>
                    <p style="float: right">Created: {{ $post->created_at }}
                </form>
                @endif
                </div>
           </div>
        </div>
   </div>
</div>
@endsection