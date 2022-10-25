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
                        <tbody>
                        
                        </tbody> 
                    </table>
                    <form method="post" action="{{ route('files.update', $file) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="upload">File:</label>
                            <input type="file" class="form-control" name="upload"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form> 
                </div>
           </div>
       </div>
   </div>
</div>
@endsection