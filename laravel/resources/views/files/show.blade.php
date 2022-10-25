@extends('layouts.app')
 
@section('content')
<style>
    .edit{
        background-color: orange; 
        margin: 10px; 
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
</style>
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{ __('File') }} {{ $file->id }}</div>
               <div class="card-body">
                   <table class="table">
                       <thead>
                            <tr>
                                <td scope="col">ID</td>
                                <td>{{ $file->id }}</td> 
                            </tr>
                            <tr>
                                <td scope="col">Filepath</td>
                                <td>{{ $file->filepath }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Filesize</td>
                                <td>{{ $file->filesize }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Created</td>
                                <td>{{ $file->created_at }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Updated</td>
                                <td>{{ $file->updated_at }}</td>
                            </tr>
                        </thead>      
                   </table>
                   <img class="img-fluid" src="{{ asset("storage/{$file->filepath}") }}" />
                </div>
           </div>
           <form method="post" action="{{ route('files.destroy', $file) }}" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <a class="btn btn-primary" href="{{ route('files.index') }}" role="button">See all Files</a>
                <a class="btn btn-primary edit" href="{{ route('files.edit', $file) }}" role="button">Edit</a>
                <button type="submit" class="btn btn-primary delete">Delete</button>
           </form>
        </div>
   </div>
</div>
@endsection