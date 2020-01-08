@extends('attendant/attendantlayout')
@section('retailA','active')
@section('body')
<?php
if (session()->exists('details')) {
      $details=session()->get('details');
  }
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Retail</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            
          </div>
          
        </div>
      </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="shad">
                    <h4 class="text-center mt-2 bord-b">Products</h4>
                    <form action="{{route('fetchproducts.post')}}" method="POST">
                    <div class="input-group mb-5" id="ajax-target">
                        <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                            <option value="" class="text-center">-Filter by Category-</option>
                            @foreach($categories as $name)
                                @if(isset($categoryid)&&($categoryid==$name->ID))
                                <option value="{{$name->ID}}" selected>{{$name->category}}</option>
                                @else
                                <option value="{{$name->ID}}">{{$name->category}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @csrf
                    </form>
                    @if(isset($products))
                    <div class='table-responsive'>
                        <table class="table table-striped table-sm text-left">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Instock</th>
                                    <th>Qty</th>
                                    <th>Add</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                    @foreach($products as $good)
                                    <form action="/attendant/handler" method="post">
                                        <tr>
                                            <td>{{$good->Product_name}}</td>
                                            <td>{{($good->unit_cost+$good->VAT)}}</td>
                                            <td>{{$good->instock}}</td>
                                            <td>
                                                <input type="number" min="1" value="1" class="qty" name="qty">
                                                <input type="hidden" name="prod_id" value="{{$good->ID}}">
                                            </td>

                                            <td><button type="submit" class="btn btn-primary"><i class='fas fa-plus'></i></button></td>
                                        </tr>
                                        @csrf
                                    </form> 
                                    @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <form action="/attendant/idsearch" method="post">
                    <label for="useid">Search by ID:</label>
                    <div class="input-group">
                        <input type="number" min="1" name="id_search" value="@if(isset($idsearch)){{$idsearch}}@endif" class="form-control" onchange="this.form.submit()">
                    </div>
                    @csrf
                    </form>

                    @if(isset($product))
                    <div class='table-responsive'>
                        <table class="table table-striped table-sm text-left">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Instock</th>
                                    <th>Qty</th>
                                    <th>Add</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                    
                                    <form action="/attendant/idhandler" method="post">
                                        <tr>
                                            <td>{{$product->Product_name}}</td>
                                            <td>{{($product->unit_cost+$product->VAT)}}</td>
                                            <td>{{$product->instock}}</td>
                                            <td>
                                                <input type="number" min="1" value="1" class="qty" name="qty">
                                                <input type="hidden" name="prod_id" value="{{$product->ID}}">
                                            </td>

                                            <td><button type="submit" class="btn btn-primary"><i class='fas fa-plus'></i></button></td>
                                        </tr>
                                        @csrf
                                    </form> 
                                   
                                
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <form action="/attendant/namesearch" method="post">
                    <label for="usename">Search by Name:</label>
                    <div class="input-group">
                        <input type="text" value="@if(isset($namesearch)){{$namesearch}}@endif" class="form-control" onchange="this.form.submit()" name="namesearch">
                    </div>
                    @csrf
                    </form>
                    @if(isset($namesearcherror))
                        <div class="errors">
                            {{$namesearcherror}}
                        </div>
                    @endif
                    @if(isset($goods))
                    <div class='table-responsive'>
                        <table class="table table-striped table-sm text-left">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Instock</th>
                                    <th>Qty</th>
                                    <th>Add</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                    @foreach($goods as $item)
                                    <form action="/attendant/idhandler" method="post">
                                        <tr>
                                            <td>{{$item->Product_name}}</td>
                                            <td>{{($item->unit_cost+$item->VAT)}}</td>
                                            <td>{{$item->instock}}</td>
                                            <td>
                                                <input type="number" min="1" value="1" class="qty" name="qty">
                                                <input type="hidden" name="prod_id" value="{{$item->ID}}">
                                            </td>

                                            <td><button type="submit" class="btn btn-primary"><i class='fas fa-plus'></i></button></td>
                                        </tr>
                                        @csrf
                                    </form> 
                                   @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <div class="shad text-center">
                    <i class="carticon align-items-center fas fa-cart-arrow-down"></i>
                    <h4 class="text-center mt-2 bord-b">Cart</h4>
        
                    <div class="table-responsive bord-b mb-2">
                         <table class="table table-striped table-sm">
                             @php
                                $cost=0;
                                $vat=0;
                             @endphp
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $item)
                                <tr>
                                    @php
                                        $cost+=($item->qty*$item->unit_cost);
                                        $vat+=($item->qty*$item->VAT);
                                    @endphp
                                    <td>{{$item->Product_name}}</td>
                                    <td>
                                        <form action="changeqty" method="post">
                                            <input type="hidden" name="cartid" value="{{$item->id}}">
                                            <input type="number" min="1" class="qty" name="newqty" value="{{$item->qty}}" onchange="this.form.submit()">
                                            <button type="submit" id="sub" style="display: none;"></button>
                                            @csrf
                                        </form>
                                    </td>
                                    <td>{{number_format((float)$item->unit_cost, 2, '.', '')}}</td>
                                    <td>{{number_format((float)($item->qty*$item->unit_cost), 2, '.', '')}}</td>
                                   
                                    <td><a href="/attendant/cartdelete/{{$item->id}}"><i class="fas fa-times close-icon"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                         </table>
                         <table class="text-left">
                             <tr>

                                 <td>Net Cost:</td>
                                 <td>{{number_format((float)$cost, 2, '.', '')}}</td>
                             </tr>
                             <tr>
                                 <td>VAT:</td>
                                 <td>{{number_format((float)$vat, 2, '.', '')}}</td>
                             </tr>
                             <tr>
                                 <td>Total Cost:</td>
                                 <td>{{number_format((float)($cost+$vat), 2, '.', '')}}</td>
                             </tr>
                            @php
                                $full=$cost+$vat;
                            @endphp
                         </table>   
                        </div>
                        <a href="/attendant/clear"><button type="button" class="btn btn-danger mr-5">Clear</button></a>
                        
                        <button  type="button" class="btn btn-success ml-5" data-toggle="modal" data-target="#myModal" @if(!($count>0))disabled @endif>Check out</button>
                        

                   
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-center">Check out</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h3>Total Cost: {{$details->symbol}} {{number_format((float)($full), 2, '.', '')}}</h3>
        <form action="/attendant/checkout" method="post">
            <label for="paymethod">Payment Method:</label>
            <div class="input-group">
                <select name="paymethod" id="paymethod" class="form-control">
                    @foreach($pay_methods as $method)
                        <option value="{{$method->id}}">{{$method->Method}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" id="checkout" style="display:none;"></button>
            @csrf
        </form>
        
      </div>

      <!-- Modal footer -->
      <div class="modal-footer ">

        <button type="button" class="btn btn-danger" id="modal-cancel mr-5" data-dismiss="modal">Cancel</button>
        <button type="button" id="check" class="btn btn-success ml-5">Paid</button>
      </div>

    </div>
  </div>
</div>
    </div>
     
      
    </main>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script type="text/javascript">

$('#check').click(function(){
    
    $('#checkout').click();
});
   
   
</script>

@endsection