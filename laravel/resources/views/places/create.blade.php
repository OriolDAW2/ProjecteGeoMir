@extends('layouts.app')
 
@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{ __('Places') }}</div>
               <div class="card-body">
                   <table class="table">
                        <thead>
                            <form method="post" action="{{ route('places.store') }}" enctype="multipart/form-data">
                                @csrf
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
                                    <div id="error"></div>
                                    <div style="margin-top: 10px">
                                        <label for="visibility_id">visibility:</label>
                                        <select name="visibility_id" class="form-control">
                                            <option value="1">Public</option>
                                            <option value="2">Contacts</option>
                                            <option value="3">Private</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
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