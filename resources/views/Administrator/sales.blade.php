@extends('Administrator/adminlayout')

@section('title','sales')
@section('salesA','active')

@section('body')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Sales</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">Export</button>
          </div>
         
        </div>
      </div>
      
        <div class="container">
            <div class="row">
                <div class="col-md-3 shad">
                    <form action="/admin/sales/filteryear" method="post">
                        <label for="yearfilter" >Filter by year</label>
                        <div class="input-group">
                            <select name="yearfilter" id="yearfilter" onchange="this.form.submit()" class="form-control">
                                <option value="">year</option>
                                @foreach($years as $year)
                                    @if(isset($yearfilter)&& $yearfilter==$year->Year)
                                        <option value="{{$year->Year}}" selected>{{$year->Year}}</option>
                                    @else
                                        <option value="{{$year->Year}}">{{$year->Year}}</option>
                                    @endif
                                
                                @endforeach
                            </select>
                        </div>
                        @csrf
                    </form>
                </div>
                <div class="col-md-1"></div>
                
                @if(isset($months))
                    <div class="col-md-3 shad">
                        <form action="/admin/sales/filtermonth" method="post">
                            <label for="monthfilter" >Filter by Month</label>
                            <div class="input-group">
                                <select name="monthfilter" id="monthfilter" onchange="this.form.submit()" class="form-control">
                                    <option value="">Month</option>
                                    @foreach($months as $month)
                                        @if(isset($monthfilter)&& $monthfilter==$month->Month)
                                            <option value="{{$month->Month}}" selected>{{$month->Month}}</option>
                                        @else
                                            <option value="{{$month->Month}}">{{$month->Month}}</option>
                                        @endif
                                    
                                    @endforeach
                                </select>
                                <input type="hidden" name="month_year" value="@if(isset($yearfilter)){{$yearfilter}}@endif">
                            </div>
                            @csrf
                        </form>
                    </div>
                @endif
                
                <div class="col-md-1"></div>
                
                @if(isset($days))
                    <div class="col-md-3 shad">
                        <form action="/admin/sales/filterday" method="post">
                            <label for="dayfilter" >Filter by day</label>
                            <div class="input-group">
                                <select name="dayfilter" id="dayfilter" onchange="this.form.submit()" class="form-control">
                                    <option value="">day</option>
                                    @foreach($days as $day)
                                        @if(isset($dayfilter)&& $dayfilter==$day->Day)
                                            <option value="{{$day->Day}}" selected>{{$day->Day}}</option>
                                        @else
                                            <option value="{{$day->Day}}">{{$day->Day}}</option>
                                        @endif
                                    
                                    @endforeach
                                </select>
                                <input type="hidden" name="day_month" value="@if(isset($monthfilter)){{$monthfilter}}@endif">
                                <input type="hidden" name="day_year" value="@if(isset($yearfilter)){{$yearfilter}}@endif">
                            </div>
                            @csrf
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="table-responsive shad" id="printarea">
            <table class="table table-bordered table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Product(s)</th>
                        <th>Quantity</th>
                        <th>Sold by</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                        <tr>
                            <td>{{date('d/m/Y',$sale->Time)}}</td>
                            <td>{{date('H:i:s',$sale->Time)}}</td>
                            <td>
                                @foreach($item[$sale->ID] as $product)
                                    {{$product->Product_name}}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($item[$sale->ID] as $product)
                                    {{$product->Quantity}}<br>
                                @endforeach
                            </td>
                            <td>{{$sale->Username}}</td>
                            <td>{{$sale->symbol}}{{number_format((float)$sale->Totalcost, 2, '.', '')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    

@endsection