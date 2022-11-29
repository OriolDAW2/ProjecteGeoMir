@extends('layouts.app')
 
@section('content')
<style>
    .ver:hover {
        background-color: #009EFF;
        border-color: #009EFF;
    }
    .edit:hover{
        background-color: orange;
        border-color: orange;
    }
    .delete:hover{
        background-color: red;
        border-color: red;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Posts') }}</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col">ID</td>
                                <td scope="col">Message</td>
                                <td scope="col">Author</td>
                                <td scope="col">Created</td>
                                <td scope="col">Updated</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->body}}</td>
                                <td>{{ $post->user->name}}</td>
                                <td>{{ $post->created_at }}</td>
                                <td>{{ $post->updated_at }}</td>
                                <td><a class="btn ver" href="{{ route('posts.show', $post) }}" role="button">👁️</a></td>
                                <td><a class="btn edit" href="{{ route('posts.edit', $post) }}" role="button">📝</a></td>
                                <td>
                                    <form method="post" action="{{ route('posts.destroy', $post) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn delete">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a class="btn btn-primary" href="{{ route('posts.create') }}" role="button">Add new post</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection