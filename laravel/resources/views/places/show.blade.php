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
               <div class="card-header">{{ __('Places') }} {{ $places->id }}</div>
               <div class="card-body">
                   <table class="table">
                       <thead>
                            <tr>
                                <td scope="col">ID</td>
                                <td>{{ $places->id }}</td> 
                            </tr>
                            <tr>
                                <td scope="col">Name</td>
                                <td>{{ $places->name }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Description</td>
                                <td>{{ $places->description }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Latitude</td>
                                <td>{{ $places->latitude }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Longitude</td>
                                <td>{{ $places->longitude }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Created</td>
                                <td>{{ $places->created_at }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Updated</td>
                                <td>{{ $places->updated_at }}</td>
                            </tr>
                        </thead>      
                   </table>
                   <img class="img-fluid" src="{{ asset("storage/{$places->file->filepath}") }}" />
                </div>
           </div>
           <form method="post" action="{{ route('places.destroy', $post) }}" enctype="multipart/form-data">
                @csrf
                @method('DELETE')
                <a class="btn btn-primary" href="{{ route('places.index') }}" role="button">See all Files</a>
                <a class="btn btn-primary edit" href="{{ route('places.edit', $post) }}" role="button">Edit</a>
                <button type="submit" class="btn btn-primary delete">Delete</button>
           </form>
        </div>
   </div>
</div>
@endsection