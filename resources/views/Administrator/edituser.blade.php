@extends('Administrator/adminlayout')
@section('title','Edit User')
@section('userA','active')
@section('body')

<?php
    
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit User</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            
          </div>
          
        </div>
      </div>


       <div class="container">
            
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-1"></div>
                <div class="col-md-6 col-sm-10">
                <div class="shad mb-5">
                    <form action="/admin/edituser/{{$user->User_ID}}" method="post">
                        <h3 class="text-center font-weight-bolder">User Details</h3>
                        
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
                        <label for="usertype">User Type:</label>
                        <div class="input-group">                           
                            <select name="usertype" id="usertype" class="form-control">
                                @foreach($types as $name)
                                    @if($name->ID==$user->UserTypesID)
                                        <option value="{{$name->ID}}" selected>{{$name->UserType}}</option>
                                    @else
                                    <option value="{{$name->ID}}">{{$name->UserType}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <label for="firstname">Firstname:</label>
                        <div class="input-group">                           
                            <input type="text" class="form-control" id="firstname" name="firstname" value="{{$user->Firstname}}">
                        </div>
                        <div class="errors">{{$errors->first('firstname')}}</div>

                        <label for="lastname">Lastname:</label>
                        <div class="input-group">                           
                            <input type="text" class="form-control" id="lastname" name="lastname" value="{{$user->Lastname}}">
                        </div>
                        <div class="errors">{{$errors->first('lastname')}}</div>

                        <label for="username">Username:</label>
                        <div class="input-group">                            
                            <input type="text" class="form-control" id="username" name="username" value="{{$user->Username}}">                           
                        </div>
                        <div class="errors">{{$errors->first('username')}}</div>

                        <label for="pword">Password:</label>
                        <div class="input-group">                           
                            <input type="password" class="form-control" id="pword" name="pword" value="{{old('pword')}}">                           
                        </div>
                        <div class="errors">{{$errors->first('pword')}}</div>

                        
                           <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
                        </div>
                        @csrf
                    </form>
                     
                </div>
                </div>
                
            </div>
        </div>
      
    </main>
 

    
       
   
@endsection