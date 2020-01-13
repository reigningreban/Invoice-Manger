<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('home');
});

Route::get('admin', function () {
    return redirect('admin/dash');
});
Route::get('attendant', function () {
    return redirect('attendant/dash');
});



Route::get('admin/products','invoiceController@products');
Route::get('admin/users','invoiceController@users');
Route::get('admin/restock','invoiceController@restock');
Route::get('admin/deleteuser/{id}','invoiceController@deleteUser');
Route::get('admin/deleteproduct/{id}','invoiceController@deleteProduct');
Route::get('admin/edituser/{id}','invoiceController@showUser');
Route::get('admin/editproduct/{id}','invoiceController@showProduct');


Route::view('home','home');

Route::get('login', function () {
    if (session()->exists('admin')) {
        return redirect('admin/dash');
    }elseif (session()->exists('attendant')) {
        return redirect('attendant/dash');
    }
    else {
        return view('login');
    }
});
// Route::post('attendant/dash','shoppingController@categoryRequest')->name('fetchproducts.post');
Route::get('attendant/filtercat/{id}','shoppingController@categoryRequest');
Route::get('tester/{id}','testController@categoryRequest');


Route::get('ajaxRequest', 'AjaxController@ajaxRequest');
Route::post('ajaxRequest', 'AjaxController@ajaxRequestPost')->name('ajaxRequest.post');

Route::get('admin/dash', 'invoiceController@index');
Route::view('attendant/receipt','Attendant/receipt');
Route::get('attendant/dash','shoppingController@index');
Route::get('attendant/mysales','shoppingController@getmysales');
Route::get('admin/statistics','shoppingController@getstats');
Route::post('admin/addcategory','shoppingController@addcategory');
Route::post('admin/edituser/{id}','invoiceController@editUser');
Route::get('attendant/idsearch/{id}','shoppingController@idsearch');
Route::get('attendant/namesearch/{name}','shoppingController@namesearch');
Route::post('admin/editproduct/{id}','invoiceController@editProduct');
Route::post('admin/adduser','invoiceController@adduser');
Route::post('admin/addproduct','invoiceController@addproduct');
Route::get('admin/addproduct','invoiceController@categories');
Route::post('login','invoiceController@login');
Route::get('tester','testController@index');
Route::get('logout','invoiceController@logout');
Route::get('autologout','invoiceController@autologout');
Route::get('admin/adduser','invoiceController@usertypes');
Route::get('attendant/clear','shoppingController@clearall');
Route::post('attendant/handler','shoppingController@add_to_cart');
Route::get('attendant/upcart','shoppingController@updatecart');
Route::get('attendant/cartcontent','shoppingController@cartcheck');
Route::get('attendant/totcost','shoppingController@totcost');
Route::post('attendant/idhandler','shoppingController@add_to_cartid')->name('addtocart');
Route::post('attendant/changeqty','shoppingController@changeqty')->name('changeqty');
Route::get('attendant/cartdelete/{id}','shoppingController@deleteproduct');


Route::post('attendant/checkout','shoppingController@checkout');
Route::post('admin/sales/filteryear','shoppingController@filteryear');
Route::post('admin/sales/filtermonth','shoppingController@filtermonth');
Route::post('admin/sales/filterday','shoppingController@filterday');
Route::post('admin/editcompany','invoiceController@editcompany');
Route::get('admin/sales','shoppingController@getsales');







