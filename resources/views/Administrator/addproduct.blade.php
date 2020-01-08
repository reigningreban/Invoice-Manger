@extends('Administrator/adminlayout')
@section('title','Add Product')
@section('addproductA','active')
@section('body')

<?php
    
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add Product</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            
          </div>
          
        </div>
      </div>


       <div class="container">
            
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-1"></div>
                <div class="col-md-6 col-sm-10">
                <div class="shad mb-5">
                    <h3 class="text-center font-weight-bolder">Product Details</h3>
                    @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
                        <div class="input-group">
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal">Add Category <i class="fas fa-plus"></i></button>
                        </div>
                    <form action="addproduct" method="post">
                        <label for="category">Category:</label>
                        <div class="input-group">                           
                            <select name="category" id="category" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $name)
                                    <option value="{{$name->ID}}">{{$name->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="errors">{{$errors->first('category')}}</div>

                        <label for="name">Product Name:</label>
                        <div class="input-group">                           
                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" autofocus>
                        </div>
                        <div class="errors">{{$errors->first('name')}}</div>

                        
                        <label for="cost">Unit Cost:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="cost" name="cost" value="{{old('cost')}}">                           
                        </div>
                        <div class="errors">{{$errors->first('cost')}}</div>

                        <label for="vat">VAT:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="vat" name="vat" value="{{old('vat')}}">                           
                        </div>
                        <div class="errors">{{$errors->first('vat')}}</div>

                        <label for="stock">In-stock:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="stock" name="stock" value="{{old('stock')}}">                           
                        </div>
                        <div class="errors">{{$errors->first('stock')}}</div>

                        <label for="reorder">Reorder Level:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="reorder" name="reorder" value="{{old('reorder')}}">                           
                        </div>
                        <div class="errors">{{$errors->first('reorder')}}</div>

                        <div class="form-group text-center">
                           <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> ADD</button>
                        </div>
                        @csrf
                    </form>
                     
                </div>
                </div>
                
            </div>
        </div>
        <div class="modal fade" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-center">Add Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="/admin/addcategory" method="post">
            <label for="categoryname"><b> Category name:</b></label>
            <div class="input-group">
                <input type="text" class="form-control" name="categoryname" id="categoryname">
            </div>
            <div class="errors" id="categoryerror">{{$errors->first('reorder')}}</div>
            <button type="submit" class="invisible" id="subCategory"></button>
            @csrf
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer ">

        <button type="button" class="btn btn-danger" id="modal-cancel mr-5" data-dismiss="modal">Cancel</button>
        <button type="button" id="add" class="btn btn-success ml-5">ADD</button>
      </div>

    </div>
  </div>
</div>
<script>
    $('#add').click(function () {
        var name=$('#categoryname').val();
        if (name!='') {
            $('#subCategory').click();
        }else{
            $('#categoryerror').html('Please enter a Category name')
        }
        
    })
</script>
    </main>
 

    
       
   
@endsection