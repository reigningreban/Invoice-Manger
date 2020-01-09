@extends('Administrator/adminlayout')

@section('title','Stats')
@section('statsA','active')

@section('body')
<?php
if (session()->exists('details')) {
      $details=session()->get('details');
  }
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Attendant Statistics</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">Export</button>
          </div>
         
        </div>
      </div>
      
        <div class="container">
            <div class="row" id="printarea">
                @foreach($attendants as $attendant)
                <div class="col-md-4">
                    <div class="table-responsive shad">
                        <table class="table table-sm font-weight-bolder">
                            <tbody>
                                <tr>
                                    <td>NAME:</td>
                                    <td>{{$attendant->Firstname}} {{$attendant->Lastname}}</td>
                                </tr>
                                <tr>
                                    <td>USERNAME:</td>
                                    <td>{{$attendant->Username}}</td>
                                </tr>
                                <tr>
                                    <td>ALL SALES:</td>
                                    <td>{{$salecount[$attendant->User_ID]}}({{$details->symbol}}{{number_format((float)$salesmade[$attendant->User_ID], 2, '.', '')}})</td>
                                </tr>
                                <tr>
                                    <td>SALES TODAY:</td>
                                    <td>{{$mysalesT[$attendant->User_ID]}}</td>
                                </tr>
                                <tr>
                                    <td>THIS MONTH:</td>
                                    <td>{{$mysalesTM[$attendant->User_ID]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        
    </main>
    

@endsection