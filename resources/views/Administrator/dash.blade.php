@extends('Administrator/adminlayout')

@section('title','Dashboard')
@section('dashA','active')
@section('body')
<?php
if (session()->exists('details')) {
      $details=session()->get('details');
  }
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            
          </div>
          
        </div>
      </div>


      <h3 class="text-white">Links</h3>
      <div class="row mb-3">
                <div class="col-xl-3 col-sm-6 py-2">
                  <a href="users">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fas fa-user fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Users</h6>
                            <h1 class="display-4">{{$userNum}}</h1>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                  <a href="products">
                    <div class="card text-white bg-secondary h-100">
                        <div class="card-body bg-secondary">
                            <div class="rotate">
                                <i class="fas fa-shopping-cart fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Products</h6>
                            <h1 class="display-4">{{$productNum}}</h1>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="#">
                    <div class="card bg-basic h-100">
                        <div class="card-body bg-basic">
                            <div class="rotate">
                                <i class="fas fa-file fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Reports</h6>
                            <h1 class="display-4">125</h1>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                  <a href="">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body bg-warning">
                            <div class="rotate">
                                <i class="fas fa-chart-pie fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Attendant Statistics</h6>
                            <h1 class="display-4"></h1>
                        </div>
                    </div>
                    </a>
                </div>
            </div>



            <div class="row mb-3">
                
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="sales">
                    <div class="card text-white bg-dark h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fas fa-credit-card fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Sales</h6>
                            <h3 class="display-6">Today: {{$todaySales}}({{$details->symbol}}{{number_format((float)$todayCash, 2, '.', '')}})</h3>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                  <a href="restock">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fas fa-shopping-basket fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Required Re-stock</h6>
                            <h1 class="display-4">{{$reorderNum}}</h1>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                  <a href="adduser">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body bg-primary">
                            <div class="rotate">
                                <i class="fas fa-users fa-4x"></i>
                            </div>
                            <h4 class="text-uppercase">Add User</h4>
                            <h1 class="display-4"><i class="fas fa-plus"></i></h1>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                  <a href="addproduct">
                    <div class="card text-white bg-dark h-100">
                        <div class="card-body bg-dark">
                            <div class="rotate">
                                <i class="fa fa-cart-plus fa-4x"></i>
                            </div>
                            <h4 class="text-uppercase">Add Products</h4>
                            <h1 class="display-4"><i class="fas fa-plus"></i></h1>
                        </div>
                    </div>
                    </a>
                </div>

            </div>

           
      </div>
      
    </main>
  
@endsection