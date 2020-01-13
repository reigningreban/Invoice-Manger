@extends('Attendant/attendantlayout')

@section('title','print reciept')
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
              <div class="col"></div>
              <div class="col shad" id="printarea">
                  <div class="container">
                    <hr style="border-top: dotted 1px;" />
                    <hr style="border-top: dotted 1px;" />
                    <h4 class="text-center">RECEIPT</h4>
                    <h5 class="text-center">{{$details->Company_name}}</h5>
                    <h6 class="text-center">{{$details->Address}}</h6>
                    <p class="text-center">{{$details->Phone_no}}</p>
                    <hr style="border-top: dotted 1px;" />
                    <hr style="border-top: dotted 1px;" />

                    <table class="table table-borderless">
                        @php
                            $vat=0;
                            $net=0;
                            $time=$invoice->Time;
                            $date=date('d/m/Y',$time);
                            $thetime=date('H:i:s',$time);

                        @endphp
                        @foreach($items as $item)
                            <tr>
                                <td>{{$item->Quantity}} X {{$item->Product_name}}</td>
                                <td class="text-right">{{number_format((float)($item->Quantity*$item->unit_cost), 2, '.', '')}}</td>
                                @php
                                    $vat+=$item->VAT;
                                    $net+=$item->unit_cost;
                                @endphp
                            </tr>
                        @endforeach
                    </table>
                        <hr style="border-top: dotted 1px;" />
                    <table class="table table-borderless">
                        <tr>
                            <td>Net</td>
                            <td class="text-right">{{number_format((float)($net), 2, '.', '')}}</td>
                        </tr>
                        <tr>
                            <td>Total VAT</td>
                            <td class="text-right">{{number_format((float)($vat), 2, '.', '')}}</td>
                        </tr>
                    </table>
                        
                        <hr style="border-top: dotted 1px;" />
                    <table class="table table-borderless">
                        <tr>
                            <td>Total Cost</td>
                            <td class="text-right">{{number_format((float)($invoice->Totalcost), 2, '.', '')}}</td>
                        </tr>
                    </table>
                    <hr style="border-top: dotted 1px;" />
                    <br>
                    <p class="text-center">
                        Payment Method:{{$invoice->Method}} <br>
                        Date:{{$date}} <br>
                        Time:{{$thetime}} <br>
                        Sold by:{{$invoice->Firstname}} {{$invoice->Lastname}}
                    </p>
                    <h4 class="text-center">
                        THANK YOU
                    </h4>
                  </div>
              </div>
              <div class="col"><button class="btn btn-primary" onclick="window.print()">Print</button></div>
              <a href="/attendant/dash"><button class="invisible" id="goback"></button></a>
          </div>
      </div>
     
</main>

<script>
    window.onload = function() { window.print(); }

    window.onafterprint = function(){
        $('#goback').click();
    }
</script>

@endsection