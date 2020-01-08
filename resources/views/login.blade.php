@extends('layout')
@section('title','Login')

@section('content')
    <header class="masthead">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
                <div class="col-4"></div>
                <div class="col-4">
                <div class="shad">
                    @if (session()->has('results'))
                        <div class="alert alert-danger">
                            {{session()->get('results')['message']}}

                        </div>
                    @endif
                    <form action="login" method="post">
                        <h3 class="text-center font-weight-bolder">Login</h3>
                        <label for="username">Username:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="username" name="username" value="@if (session()->has('results')){{session()->get('results')['username']}}@else{{old('username')}}@endif"
                            <?php if (!session()->exists('results')) echo('autofocus')?> >
                        </div>
                        <div class="errors"></div>
                        <label for="password">Password:</label>
                        <div class="input-group">                            
                            <div class="input-group-prepend"><span class="input-group-text" onclick="passify()"><i id="eyecon" class="fas fa-eye-slash"></i></span></div>
                            <input type="password" class="form-control" id="password" name="password" value="" <?php if (session()->exists('results')) echo('autofocus')?> >
                            
                        </div>
                        
                        @if (\Session::has('pass_crash'))
                            <div class="error">
                                {!! \Session::get('pass_crash') !!}
                            </div>
                        @endif
                        <div class="errors">{{$errors->first()}}</div>
                        <div class="form-group text-center">
                           <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        
                        @csrf
                    </form>
                </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </header>
@endsection