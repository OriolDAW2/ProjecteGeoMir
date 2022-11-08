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
               <div class="card-header">{{ __('Places') }}</div>
               <div class="card-body">
                   <table class="table">
                       <thead>
                           <tr>
                               <td scope="col">ID</td>
                               <td scope="col">Name</td>
                               <td scope="col">Description</td>
                               <td scope="col">Created</td>
                               <td scope="col">Updated</td>
                               <td></td>
                           </tr>
                       </thead>
                       <tbody>
                           @foreach ($places as $place)
                           <tr>
                               <td>{{ $place->id }}</td>
                               <td>{{ $place->name }}</td>
                               <td>{{ $place->description }}</td>
                               <td>{{ $place->latitude }}</td>
                               <td>{{ $place->longitude }}</td>
                               <td>{{ $place->created_at }}</td>
                               <td>{{ $place->updated_at }}</td>
                               <td><a class="btn ver" href="{{ route('places.show', $place) }}" role="button">👁️</a></td>
                               <td><a class="btn edit" href="{{ route('places.edit', $place) }}" role="button">📝</a></td>
                               <td><form method="post" action="{{ route('places.destroy', $place) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete">🗑️</button>
                                </form></td>
                           </tr>
                           @endforeach
                       </tbody>
                   </table>
                   <a class="btn btn-primary" href="{{ route('places.create') }}" role="button">Add new Place</a>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection


