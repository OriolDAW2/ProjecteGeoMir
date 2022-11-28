@extends('layouts.app')
 
@section('content')
<style>
    .error{
        color: red;
        padding: 5px;
        font-weight: bold;
    }
</style>
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{ __('Create Posts') }}</div>
               <div class="card-body">
                   <table class="table">
                        <thead>
                            <form id="create_posts" method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                                @csrf
                                @vite('resources/js/posts/create.js')
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label>Message:</label>
                                    <textarea class="form-control" name="body" id="body"></textarea>
                                    <div class="error" id="error_body"></div>
                                    <label>Latitude:</label>
                                    <input type="text" class="form-control" name="latitude"></input>
                                    <div class="error" id="error_lat"></div>
                                    <label>Longitude:</label>
                                    <input type="text" class="form-control" name="longitude"></input>
                                    <div class="error" id="error_long"></div>
                                    <label for="upload">File:</label>
                                    <input type="file" class="form-control" name="upload"/>
                                    <div class="error" id="error_file"></div>
                                    <div style="margin-top: 10px">
                                        <label for="visibility_id">Visibility:</label>
                                        <select name="visibility_id" class="form-control">
                                            <option value="1">Public</option>
                                            <option value="2">Contacts</option>
                                            <option value="3">Private</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Post</button>
                                <button type="reset" class="btn btn-secondary" style="margin-left: 10px">Reset</button>
                            </form>
                        </thead>    
                   </table>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection