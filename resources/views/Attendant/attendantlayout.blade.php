@extends('layout')
@section('title','Attendant')

@section('content')
<?php
  if (session()->exists('attendant')) {
      $data=session()->get('attendant');
  }
  $company=DB::table('company')
        ->join('currency','currency.id','=','Currency_id')
        ->where('company.ID',1)->first();
        $companyname=$company->Company_name;
        $currency=$company->symbol;
?>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">{{$companyname}} </a>
  
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link top" href="/logout"><button class="btn btn-outline-secondary signout"><i class="fas fa-power-off"></i><b> Logout</b></button></a>
    </li>
  </ul>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link @yield('retailA')" href="dash">
              <span data-feather="home"><i class="fas fa-money-check-alt"></i></span>
              Retail <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('salesA')" href="mysales">
              <span data-feather="file"><i class="fas fa-chart-pie"></i></span>
              My sales
            </a>
          </li>
          
        </ul>

        
      </div>
    </nav>

    @yield('body')
    
    </div>
</div>
    <footer class="footer py-2 mt-auto dark">
  <div class="container-fluid">
      <div class="row">
        <div class="col-md-1"></div>
          <div class="col-md-4"><span class="text-muted">All rights reserved©O'Bounce Tech 2019</span></div>
          <div class="col-md-3"></div>
          <div class="col-md-3">
             
            <span class='text-white'>You Logged in as {{$data['username']}} at {{$data['time']}}<span>
              
          </div>
          <div class="col-md-1">
            
          </div>
      </div>
    <a href="/autologout" id="logout-link"></a>

  </div>
  <script src="{{asset('js/autologout.js')}}"></script>
</footer>


@endsection