@extends('Administrator/adminlayout')
@section('title','Edit Product')
@section('productsA','active')
@section('body')

<?php
    
?>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Product</h1>
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
                    <form action="/admin/editproduct/{{$product->ID}}" method="post">
                        <h3 class="text-center font-weight-bolder">Product Details</h3>
                        
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
                        <label for="category">Category</label>
                        <div class="input-group">                           
                            <select name="category" id="category" class="form-control">
                                @foreach($categories as $name)
                                    @if($name->ID==$product->CategoriesID)
                                        <option value="{{$name->ID}}" selected>{{$name->category}}</option>
                                    @else
                                        <option value="{{$name->ID}}">{{$name->category}}</option>
                                    @endif
                                @endforeach
                                
                            </select>
                        </div>

                        <label for="name">Product Name:</label>
                        <div class="input-group">                           
                            <input type="text" class="form-control" id="name" name="name" value="{{$product->Product_name}}" autofocus>
                        </div>
                        <div class="errors">{{$errors->first('name')}}</div>

                        
                        <label for="cost">Unit Cost:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="cost" name="cost" value="{{$product->unit_cost}}">                           
                        </div>
                        <div class="errors">{{$errors->first('cost')}}</div>

                        <label for="vat">VAT:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="vat" name="vat" value="{{$product->VAT}}">                           
                        </div>
                        <div class="errors">{{$errors->first('vat')}}</div>

                        <label for="stock">In-stock:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="stock" name="stock" value="{{$product->instock}}">                           
                        </div>
                        <div class="errors">{{$errors->first('stock')}}</div>

                        <label for="reorder">Reorder Level:</label>
                        <div class="input-group">                            
                            <input type="number" min="0" step="0.01" class="form-control" id="reorder" name="reorder" value="{{$product->Reorder_level}}">                           
                        </div>
                        <div class="errors">{{$errors->first('reorder')}}</div>

                        <div class="form-group text-center">
                           <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
                        </div>
                        @csrf
                    </form>
                     
                </div>
                </div>
                
            </div>
        </div>
      
    </main>
 

    
       
   
@endsection