@extends('layout')
@section('title','Admin')

@section('content')
<?php
use Illuminate\Support\Facades\DB;
  if (session()->exists('admin')) {
      $data=session()->get('admin');
  }
  $company=DB::table('company')
        ->join('currency','currency.id','=','Currency_id')
        ->where('company.ID',1)->first();
        $companyname=$company->Company_name;
        $currencyid=$company->id;
        $currency=$company->symbol;
        
        
  $currencies=DB::table('currency')->get();
?>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">{{$companyname}} </a>
  
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link " href="/logout"><button class="btn btn-outline-secondary signout"><i class="fas fa-power-off"></i> Logout</button></a>
    </li>
  </ul>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link @yield('dashA')" href="/admin/dash">
              <span data-feather="home"><i class="fas fa-tachometer-alt"></i></span>
              Dashboard <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('userA')" href="/admin/users">
              <span data-feather="file"><i class="fas fa-user"></i></span>
              Users
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('productsA')" href="/admin/products">
              <span data-feather="shopping-cart"><i class="fas fa-shopping-cart"></i></span>
              Products
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link @yield('salesA')" href="/admin/sales">
              <span data-feather="bar-chart-2"><i class="fas fa-credit-card"></i></span>
              Sales
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('restockA')" href="/admin/restock">
              <span data-feather="bar-chart-2"><i class="fas fa-shopping-basket"></i></span>
              Required Re-stock
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('statsA')" href="/admin/statistics">
              <span data-feather="bar-chart-2"><i class="fas fa-chart-pie"></i></span>
              Attendant Statistics
            </a>
          </li>
        </ul>
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Action</span>
          
        </h6>
        <ul class="nav flex-column mb-2">
        <li class="nav-item">
            <a class="nav-link @yield('adduserA')" href="/admin/adduser">
              <span data-feather="layers"><i class="fas fa-users"></i></span>
              Add User
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link @yield('addproductA')" href="/admin/addproduct">
              <span data-feather="layers"><i class="fas fa-cart-plus"></i></span>
              Add Product
            </a>
          </li>
          <li class="nav-item" data-toggle="modal" data-target="#myModal">
            <a class="nav-link @yield('')" href="" id='detailslink'>
              <span data-feather="layers"><i class="fas fa-cog" ></i></span>
              Company Details
            </a>
          </li>
        </ul>
        
      </div>
    </nav>

    @yield('body')
    
    </div>
    <div class="modal fade" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-center">Edit Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="/admin/editcompany" method="post">
          <label for="company_name">Company Name</label>
          <div class="input-group mb-1">
            <input type="text" class="form-control" name="company_name" value="{{$companyname}}">
          </div>
          <label for="company_name">Phone Number</label>
          <div class="input-group mb-1">
            <input type="tel" class="form-control" name="company_phone" value="{{$company->Phone_no}}">
          </div>
          <label for="company_name">Email Address</label>
          <div class="input-group mb-1">
            <input type="email" class="form-control" name="company_email" value="{{$company->Email}}">
          </div>
          <label for="company_name">Address</label>
          <div class="input-group mb-1">
            <input type="text" class="form-control" name="company_address" value="{{$company->Address}}">
          </div>
          <label for="currency">Country(Currency)</label>
          <div class="input-group">
            <select name="currency" id="currency" class="form-control">
              @foreach($currencies as $single)
                @if($currencyid==$single->id)
                  <option value="{{$single->id}}" selected>{{$single->country}}({{$single->symbol}})</option>
                @else
                  <option value="{{$single->id}}">{{$single->country}}({{$single->symbol}})</option>
                @endif
              @endforeach
            </select>
            <button type="submit" class="invisible" id="detailsubmit">Submit</button>
          </div>
          @csrf
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer ">

        <button type="button" class="btn btn-danger" id="modal-cancel mr-5" data-dismiss="modal">Cancel</button>
        <button type="button" id="check" class="btn btn-success ml-5">Update</button>
      </div>

    </div>
  </div>
</div>

</div>
    <footer class="footer py-2 mt-auto dark">
  <div class="container-fluid">
      <div class="row">
        <div class="col-md-1 text-white"></div>
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
  <script>
    $('#check').click(function () {
      $('#detailsubmit').click();
    })
    $('#detailslink').click(function (e) {
      e.preventDefault();
    })
  </script>
</footer>


@endsection