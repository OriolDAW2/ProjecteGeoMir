@extends('layouts.app')
 
@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{ __('Files') }}</div>
               <div class="card-body">
                   <table class="table">
                        <thead>
                            <form method="post" action="{{ route('places.update'), $post }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group" style="margin-bottom: 10px;">

                                    <label >Name:</label>
                                    <input type="text" class="form-control" name="name"/>

                                    <label >Description:</label>
                                    <textarea type="text" class="form-control" name="description"></textarea>

                                    <label >Longitude:</label>
                                    <input type="text" class="form-control" name="longitude"/>

                                    <label >Latitude:</label>
                                    <input type="text" class="form-control" name="latitude"/>

                                    <label for="upload">File:</label>
                                    <input type="file" class="form-control" name="upload"/>

                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </thead>    
                   </table>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection