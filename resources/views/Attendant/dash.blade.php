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
                    <div class="input-group mb-5" id="ajax-target">
                        <select name="category" id="category" class="form-control">
                            <option value="0" class="text-center">-Filter by Category-</option>
                            @foreach($categories as $name)
                                @if(isset($categoryid)&&($categoryid==$name->ID))
                                <option value="{{$name->ID}}" selected>{{$name->category}}</option>
                                @else
                                <option value="{{$name->ID}}">{{$name->category}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    
                        <div id="categoryfilter">
                            
                        </div>
                    
                    
                    <label for="useid">Search by ID:</label>
                    <div class="input-group">
                        <input type="number" min="1" id="id_search" value="" class="form-control">
                    </div>
                   
                    <div id="idsearch" class="mb-3">

                    </div>
                    
                    
                    <label for="usename">Search by Name:</label>
                    <div class="input-group">
                        <input type="text" id="name_search" value="" class="form-control" name="namesearch">
                    </div>
                    <div id="namesearch" class="mb-3">

                    </div>
                    
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <div class="shad text-center">
                    <i class="carticon align-items-center fas fa-cart-arrow-down"></i>
                    <h4 class="text-center mt-2 bord-b">Cart</h4>
                    <div id="carttable">

                    </div>
                     <a href="/attendant/clear" id="clearcart"><button id="clearcartbtn" type="button" class="btn btn-danger mr-5">Clear</button></a>
                        
                        <button  type="button" id="checkoutbtn" class="btn btn-success ml-5" data-toggle="modal" data-target="#myModal">Check out</button>
                        

                   
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
        <h3>Total Cost: {{$details->symbol}} <span id="totcost"></span></h3>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">

$('#check').click(function(){
    
    $('#checkout').click();
});
$('#checkoutbtn').click(function () {
    var cost=document.getElementById('containscost').innerHTML;
    document.getElementById('totcost').innerHTML=cost;
})

$("body").on("click",".subtocart", function(){
    
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        $.ajax({ 
            data: $(this).parent().serialize(), 
            type: $(this).parent().attr('method'),
            url: $(this).parent().attr('action'),
            success: function() {
                cart();
            }
        });
        return false; 
   
});

$("body").on("click",".cartremove", function(e){
    e.preventDefault();
   var link=$(this).attr('href');
   $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           cart();
       });

});

$("body").on("click","#clearcart", function(e){
    e.preventDefault();
   var link=$(this).attr('href');
   $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           cart();
       });

});

$("body").on("change",".qtychange", function(){
    $(document).on("keydown", "form", function(event) { 
        return event.key != "Enter";
    });
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
$.ajax({ 
    data: $(this).parent().serialize(), 
    type: $(this).parent().attr('method'),
    url: $(this).parent().attr('action'),
    success: function() {
        cart();
    }
});

return false; 

});





 function cart() {
    $.get('/attendant/upcart', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('carttable').innerHTML =data;
       });
       $.get('/attendant/cartcontent', function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           if (data=='no') {
                $('#checkoutbtn').attr('disabled', 'disabled');
                $('#clearcartbtn').attr('disabled', 'disabled');
           }else{
            $('#checkoutbtn').removeAttr('disabled');
            $('#clearcartbtn').removeAttr('disabled');
           }
           
       });
 }


   $(document).ready(function(){
    $(document).on("keydown", "form", function(event) { 
        return event.key != "Enter";
    });
        cart();
        
  $("#category").change(function(){
      
      var cat=$("#category").val();
      var link="/attendant/filtercat/";
      link+=cat;
      $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('categoryfilter').innerHTML =data;
       });
    
  });


  $("#id_search").keyup(function(){
      var cat=$("#id_search").val();
      if (cat!="") {
          var link="/attendant/idsearch/";
      link+=cat;
      $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('idsearch').innerHTML =data;
       });
      }else{
        document.getElementById('idsearch').innerHTML ="<div></div>"
      }
      
    
  });
  $("#id_search").change(function(){
      var cat=$("#id_search").val();
      if (cat!="") {
          var link="/attendant/idsearch/";
      link+=cat;
      $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('idsearch').innerHTML =data;
       });
      }else{
        document.getElementById('idsearch').innerHTML ="<div></div>"
      }
      
    
  });


  $("#name_search").keyup(function(){
      var cat=$("#name_search").val();
      if (cat!="") {
          var link="/attendant/namesearch/";
      link+=cat;
      $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('namesearch').innerHTML =data;
       });
      }else{
        document.getElementById('namesearch').innerHTML ="<div></div>"
      }
      
    
  });

  
  
});


</script>

@endsection