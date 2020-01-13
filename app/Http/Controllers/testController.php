<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DateTime;

class testController extends Controller
{
    public function index()
    {  

        
        $num=87;
      $invoices=DB::table('invoices')->get();
      foreach ($invoices as $invoice ) {
          
         
          DB::update('update invoices set currency_id=?  WHERE ID = ?',[$num,$invoice->ID]);
          
      }
      return redirect('/');
    }
    public function logout()
    {
        session()->flush();
        return redirect('login');
    }
    public function categoryRequest($id)
     {
    //     $data=session()->get('attendant');
    //     $userId=$data['id'];
    //     $count=DB::table('cart')->whereRaw('user_id = ?',[$userId])->count();
        // $categoryid=request('category');
        $products=DB::table('products')->where('CategoriesID',$id)->get();
        // $categories=DB::table('categories')->get();
        // $pay_methods=DB::table('pay_methods')->get();
        // $cart=DB::table('cart')->join('Products','Products.ID','=','cart.product_id')
        // ->where('cart.user_id',$userId)->get();
        $table=
        "    <div class='table-responsive' >
        <table class='table table-striped table-sm text-left'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Instock</th>
                    <th>Qty</th>
                    <th>Add</th>
                </tr>
            </thead>
            <tbody>";
            
                    foreach($products as $good){
                     $table.=   "
                            <tr>
                            <td>$good->Product_name</td>
                            <td>($good->unit_cost+$good->VAT)</td>
                            <td>+$good->instock+</td>
                            <td>
                                <input type='number' min='1' value='1' class='qty' name='qty'>
                                <input type='hidden' name='prod_id' value='$good->ID'>
                            </td>

                            <td><button type='submit' class='btn btn-primary'><i class='fas fa-plus'></i></button></td>
                        </tr>
                        ";
                    }

                    $table.="
                      </tbody>
        </table>
    </div>"
                  
                
          ;
           
         dd($table);
     }
}
