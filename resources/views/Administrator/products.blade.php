@extends('Administrator/adminlayout')

@section('title','products')
@section('productsA','active')

@section('body')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Products</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">Export</button>
          </div>
         
        </div>
      </div>
      <div>
                         @if (\Session::has('success'))
                            <div class="container alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
      <div class="container ">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8 shad mb-3">
            <label for="name_search">Search:</label>
            <div class="input-group">
              <input type="text" class="form-control" id="name_search" name="name_search" placeholder="Search...">
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
      <div class="container shad" id="namesearch"></div>
      <div id="printarea">
        @foreach($categories as $name)
            
      <div class="shad">
      <h3 class="text-center">Category {{$name->ID}}: {{$name->category}} </h3>
      <div class="table-responsive ">
        <table class="table table-striped table-sm prodlist">
          <thead>
            
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Unit Cost</th>
              <th></th>
              <th>VAT</th>
              <th>Instock</th>
              <th>Reorder level</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
          @php
            $list=$products[$name->ID];
          @endphp
            @foreach($list as $good)

               

               

               
                      <tr>
                            <td>{{$good->ID}}</td>
                            <td>{{$good->Product_name}}</td>
                            <td  class="text-right ">{{number_format((float)$good->unit_cost, 2, '.', '')}}</td>
                            <td></td>
                            <td>{{number_format((float)$good->VAT, 2, '.', '')}}</td>
                            <td>{{$good->instock}}</td>
                            <td>{{$good->Reorder_level}}</td>
                            <td><a href="editproduct/{{$good->ID}}"><button type="button" class="btn btn-primary"><i class="far fa-edit"></i></button></a></td>
                            <td><a href="deleteproduct/{{$good->ID}}" onclick="javascript: return confirm('Are you sure you want delete?')"><button type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button></a></td>

                        </tr>
               
            
            @endforeach
             
          
          </tbody>
        </table>
      </div>
     </div>
      @endforeach
      </div>
      </div>
    </main>
    
    <script>
    $(document).ready(function() {
        $('.prodlist').DataTable();
    } );
    $("#name_search").keyup(function(){
      var cat=$("#name_search").val();
      if (cat!="") {
          var link="/admin/namesearchadmin/";
      link+=cat;
      $.get(link, function(data, status){
            let myresult = ("Data: " + data + "\nStatus: " + status);
            
           document.getElementById('namesearch').innerHTML =data;
       });
      }else{
        document.getElementById('namesearch').innerHTML ="<div></div>"
      }
      
    
  });
  </script>
@endsection