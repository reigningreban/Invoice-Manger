@extends('layout')

@section('content')
    <header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center">
        <div class="col-12 text-center">
            <h1 class="font-weight-bolder text-danger ">Error</h1>
            <p class="lead font-weight-bold">The page you requested is not available</p>

            <p>You may have entered a wrong url please click the button below to return to the homepage and navigate from there</p>
            <p>
                <a href="/"><button class="btn btn-primary">Return to home</button></a>
            </p>
        </div>
        </div><a href="/autologout" id="logout-link"></a>

    </div>
    </header>
    <script src="{{asset('js/autologout.js')}}"></script>
@endsection