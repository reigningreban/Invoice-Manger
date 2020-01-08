@extends('Administrator/adminlayout')

@section('title','Users')
@section('userA','active')

@section('body')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
           
          </div>
          
        </div>
      </div>

                        @if (\Session::has('success'))
                            <div class="container alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
            
        
      
      <div class="table-responsive shad">
        <table class="table table-striped table-sm">
          <thead>
            
            <tr>
              <th>User ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Username</th>
              <th>User Type</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
          
            @foreach($users as $user)

               

               
                      <tr>
                            <td>{{$user->User_ID}}</td>
                            <td>{{$user->Firstname}}</td>
                            <td>{{$user->Lastname}}</td>
                            <td>{{$user->Username}}</td>
                            <td>{{$user->UserType}}</td>
                            <td>@if($user->ID!=1)<a href="edituser/{{$user->User_ID}}"><button type="button" class="btn btn-primary"><i class="fas fa-user-edit"></i></button></a>@endif</td>
                            <td>@if($user->ID!=1)<a href="deleteuser/{{$user->User_ID}}" onclick="javascript: return confirm('Are you sure you want delete?')"><button type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button></a>@endif</td>
                        </tr>
               
             
            @endforeach
             
         
          </tbody>
        </table>
      </div>
     
    </main>
    
   
@endsection