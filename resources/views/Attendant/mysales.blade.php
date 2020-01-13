@extends('attendant/attendantlayout')
@section('salesA','active')
@section('body')


<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">My sales</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            
          </div>
          
        </div>
      </div>
    @if(isset($salecount))
    <div class="container">
      <div class="row shad font-weight-bold">
        <div class="col"></div>
        <div class="col">Sale count: {{$salecount }}</div>
        <div class="col">Sales This month: {{$mysalesTM }}</div>
        <div class="col">Sales Today: {{$mysalesT}} </div>
        <div class="col"></div>
        
      </div>
      
    <div class="table-responsive shad">
            <table class="table table-bordered table-sm" id="mysales">
                <thead class="thead-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Product(s)</th>
                        <th>Quantity</th>
                        
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
                            
                            <td>{{$sale->symbol}}{{number_format((float)$sale->Totalcost, 2, '.', '')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="container">
        <div class="row shad text-center">
            <h3 text-center>You don't have any sales yet</h3>
        </div>
    </div>
     @endif
      
    </main>
    
    <script>
    $(document).ready(function() {
        $('#mysales').DataTable();
    } );
  </script>
@endsection
