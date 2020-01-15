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
                
            </div>
        </div>

        <div class="table-responsive shad" id="printarea">
            <table class="table table-bordered table-sm" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
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
                            <td>{{$sale->ID}}</td>
                            <td>{{date('d-m-Y',$sale->Time)}}</td>
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
    
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    } );
  </script>
@endsection