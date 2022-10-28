@extends('layouts.app')
 
@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{ __('Edit Posts') }}</div>
               <div class="card-body">
                   <table class="table">
                        <thead>
                            <form method="post" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label>Title:</label>
                                    <input type="text" class="form-control" name="title"></input>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label>Message:</label>
                                    <textarea class="form-control" name="message"></textarea>
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label for="upload">File:</label>
                                    <input type="file" class="form-control" name="upload"/>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Post</button>
                            </form>
                        </thead>    
                   </table>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection