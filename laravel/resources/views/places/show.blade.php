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
    .fav{
        background-color: green; 
        margin-left: 5px;
        border-color: green;
        float: right;
        margin-top: -25px;
    }
    .fav:hover{
        background-color: #1E894D;
        border-color: #1E894D;
    }
    .unfav{
        background-color: red; 
        margin-left: 5px;
        border-color: red;
        float: right;
        margin-top: -25px;
    }
    .unfav:hover{
        background-color: #E74C3C;
        border-color: #E74C3C;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Place') }} {{ $place->id }}
                @if($place->isFavorite())
                <form method="post" action="{{ route('places.favorite', $place) }}" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-primary fav" role="button">Favorite</button>
                </form>
                @else
                <form method="post" action="{{ route('places.unfavorite', $place) }}" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-primary unfav" role="button">Unfavorite</button>
                </form>
                @endif
               </div>
               <div class="card-body">
                   <table class="table">
                       <thead>
                            <tr>
                                <td scope="col">ID</td>
                                <td>{{ $place->id }}</td> 
                            </tr>
                            <tr>
                                <td scope="col">Name</td>
                                <td>{{ $place->name }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Description</td>
                                <td>{{ $place->description }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Latitude</td>
                                <td>{{ $place->latitude }}</td>
                            </tr>
                            <tr>
                                <td scope="col">Longitude</td>
                                <td>{{ $place->longitude }}</td>
                            </tr>
                        </thead>      
                    </table>
                    <img class="img-fluid" src="{{ asset("storage/{$place->file->filepath}") }}" />
                </div>
                <div class="card-footer">
                <form method="post" action="{{ route('places.destroy', $place) }}" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('places.index') }}" role="button">See all Places</a>
                    <a class="btn btn-primary edit" href="{{ route('places.edit', $place) }}" role="button">Edit</a>
                    <button type="submit" class="btn btn-primary delete">Delete</button>
                    <p style="float: right">
                    Author: {{ $place->user->name}}<br>
                    Created: {{ $place->created_at }}
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection