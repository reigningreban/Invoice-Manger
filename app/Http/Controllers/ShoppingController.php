<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class ShoppingController extends Controller
{
    //Function to check if an admin is logged in
    public function getattlog()
    {
        if (!session()->exists('attendant')) {
            return false;
        }else {
            return true;
        }   
    }
     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for filtering products 
    public function index()
    {
        $logged=$this->getattlog();
        //check if an attendant is logged in
     if (!$logged) {
         return redirect('login');
     }else {
        $data=session()->get('attendant');
        $userId=$data['id'];
        $count=DB::table('cart')->whereRaw('user_id = ?',[$userId])->count();
         //get categories
         $categories=DB::table('categories')->get();
         $pay_methods=DB::table('pay_methods')->get();
         $cart=DB::table('cart')->join('Products','Products.ID','=','cart.product_id')
        ->where('cart.user_id',$userId)->get();
        
         //return the add user view
        return view('Attendant/dash',[
            'categories'=>$categories,
            'cart'=>$cart,
            'count'=>$count,
            'pay_methods'=>$pay_methods
        ]);
     } 
    }

    public function getadminlog()
    {
        if (!session()->exists('admin')) {
            return false;
        }else {
            return true;
        }   
    }

     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function categoryRequest($id)
     {
         $logged=$this->getattlog();
         if (!$logged) {
             return redirect('login');
         }else{

         
        if (($id==0)) {
            return "<div></div>";
        }else {
            $products=DB::table('products')->where('CategoriesID',$id)->get();
        
            $table=
            "    <div class='table-responsive' >
            <table class='table table-striped table-sm text-left'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Instock</th>
                        <th>Qty &nbsp &nbsp   Add</th>
                    </tr>
                </thead>
                <tbody>";
                
                        foreach($products as $good){
                            $tot=$good->unit_cost+$good->VAT;
                        $table.=   "
                                <tr>
                                    
                                        <td>$good->Product_name</td>
                                        <td>$tot</td>
                                        <td>$good->instock</td>
                                        <td>
                                            
                                    
                                            <form action='/attendant/handler' class='cartadd' method='POST'>
                                                <input type='number' min='1' value='1' class='qty' name='qty'> &nbsp
                                                <input type='hidden' name='prod_id' value='$good->ID'>
                                                <button type='submit' class='btn btn-primary subtocart' value='submit'>
                                                    <i class='fas fa-plus'></i>
                                                </button>
                                            </form>
                                        </td>
                                    
                                </tr>
                            ";
                        }

                        $table.="
                        </tbody>
            </table>
        </div>"
            ;
            
            return $table;
        
        }
         
    }
        
    }


    

    public function updatecart()
    {
        $logged=$this->getattlog();
        if (!$logged) {
            return redirect('login');
        }else {
            
       
        $data=session()->get('attendant');
        $userId=$data['id'];
        $count=DB::table('cart')->whereRaw('user_id = ?',[$userId])->count();
        $cart=DB::table('cart')->join('Products','Products.ID','=','cart.product_id')
        ->where('cart.user_id',$userId)->get();
        $cost=0;
        $vat=0;
        $table="
        <div class='table-responsive bord-b mb-2'>
                         <table class='table table-striped table-sm'>
                            
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>remove</th>
                                </tr>
                            </thead>
                            <tbody id='carttable'>
                            ";
                            foreach($cart as $item){
                                $cost+=($item->qty*$item->unit_cost);
                                $vat+=($item->qty*$item->VAT);
                               $table.= 
                               "<tr>
                    
                                    <td><span class='limited'>$item->Product_name<span></td>
                                    <td>
                                        <form action='changeqty' method='post'>
                                            <input type='hidden' name='cartid' value='$item->id'>
                                            <input type='number' min='1' class='qty qtychange' name='newqty' value='$item->qty'>
                                            
                                        </form>
                                    </td>
                                    <td>".number_format((float)$item->unit_cost, 2, '.', '')."</td>
                                    <td>".number_format((float)($item->qty*$item->unit_cost), 2, '.', '')."</td>
                                
                                    <td><a href='/attendant/cartdelete/$item->id' class='cartremove'><i class='fas fa-times close-icon'></i></a></td>
                                </tr>";
                            }

                           $table .=" </tbody>
                         </table>
                         <table class='text-left'>
                             <tr>

                                 <td>Net Cost:</td>
                                 <td>".number_format((float)$cost, 2, '.', '')."</td>
                             </tr>
                             <tr>
                                 <td>VAT:</td>
                                 <td>".number_format((float)$vat, 2, '.', '')."</td>
                             </tr>
                             <tr>
                                 <td>Total Cost:</td>
                                 <td id='containscost'>".number_format((float)($cost+$vat), 2, '.', '')."</td>
                             </tr>
                            
                                
                            
                         </table>   
                        </div>";
        
        
        return $table;
                        }
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function categoryRequestid($id)
    {
        $data=session()->get('attendant');
        $userId=$data['id'];
        $count=DB::table('cart')->whereRaw('user_id = ?',[$userId])->count();
        $categoryids=DB::table('products')->select('CategoriesID')->where('ID',$id)->first();
        $categoryid=$categoryids->CategoriesID;
        $pay_methods=DB::table('pay_methods')->get();
        $products=DB::table('products')->where('CategoriesID',$categoryid)->get();
        $categories=DB::table('categories')->get();
        $cart=DB::table('cart')->join('Products','Products.ID','=','cart.product_id')
        ->where('cart.user_id',$userId)->get();
        
        return view('/attendant/dash',[
            'products'=>$products,
            'categories'=>$categories,
            'categoryid'=>$categoryid,
            'cart'=>$cart,
            'count'=>$count,
            'pay_methods'=>$pay_methods
        ]);
    }
       //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function add_to_cart()
    {
        $id=request('prod_id');
        $qty=request('qty');
        $data=session()->get('attendant');
        $userId=$data['id'];
        $count=DB::table('cart')->whereRaw('product_id = ? and user_id = ?',[$id,$userId])->count();
        if ($count>0) {
            $cart=DB::table('cart')->whereRaw('product_id = ? and user_id = ?',[$id,$userId])->first();
            $dataqty=$cart->qty;
            $cartid=$cart->id;
            $newqty=$qty+$dataqty;
            DB::update('update cart set qty=?  WHERE id = ? and user_id=?',[$newqty,$cartid,$userId]);
        }else{
            DB::table('cart')->insert([
            'user_id'=>$userId,
            'product_id'=>$id,
            'qty'=>$qty,
        ]);
        }
        

       return 'done';
    }

    public function cartcheck()
    {
        
        $data=session()->get('attendant');
        $userId=$data['id'];
        $count=DB::table('cart')->whereRaw('user_id = ?',[$userId])->count();
        if ($count>0) {
            return "yes";
        }else{
           return 'no';
        }
        

       
    }
     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function deleteproduct($id)
    {
       
        $data=session()->get('attendant');
        $userId=$data['id'];
        DB::delete('delete from cart where id = ? and user_id=?',[$id,$userId]);
        
        return redirect('attendant/dash');
    }

     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function changeqty()
    {
       
        $data=session()->get('attendant');
        $userId=$data['id'];
        $cartid=request('cartid');
        $newqty=request('newqty');
        DB::update('update cart set qty=?  WHERE id = ? and user_id=?',[$newqty,$cartid,$userId]);
        
        
        return redirect('attendant/dash');
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function clearall()
    {
       
        $data=session()->get('attendant');
        $userId=$data['id']; 
        DB::delete('delete from cart where user_id=?',[$userId]);
        
        
        return redirect('attendant/dash');
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function idsearch($id)
    {
       
        $data=session()->get('attendant');
        $userId=$data['id'];
        
        $product=DB::table('Products')->where('ID',$id)->first();
        
        if(isset($product)){

        
            $table=
            "    <div class='table-responsive' >
            <table class='table table-striped table-sm text-left'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Instock</th>
                        <th>Qty &nbsp &nbsp   Add</th>
                    </tr>
                </thead>
                <tbody>";
                
                       
                            $tot=$product->unit_cost+$product->VAT;
                        $table.=   "
                                <tr>
                                    
                                        <td>$product->Product_name</td>
                                        <td>$tot</td>
                                        <td>$product->instock</td>
                                        <td>
                                            
                                    
                                            <form action='/attendant/handler' class='cartadd' method='POST'>
                                                <input type='number' min='1' value='1' class='qty' name='qty'> &nbsp
                                                <input type='hidden' name='prod_id' value='$product->ID'>
                                                <button type='submit' class='btn btn-primary subtocart' value='submit'>
                                                    <i class='fas fa-plus'></i>
                                                </button>
                                            </form>
                                        </td>
                                    
                                </tr>
                            ";
                        

                        $table.="
                        </tbody>
            </table>
        </div>"
            ;
            
            return $table;
        
            }else{
                return "<div class='errors text-center'>No product found</div>";
            }
        
         
        
         //return the add user view
       
        
    }

       //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function add_to_cartid()
    {
        $id=request('prod_id');
        $qty=request('qty');
        $data=session()->get('attendant');
        $userId=$data['id'];
        $count=DB::table('cart')->whereRaw('product_id = ? and user_id = ?',[$id,$userId])->count();
        if ($count>0) {
            $cart=DB::table('cart')->whereRaw('product_id = ? and user_id = ?',[$id,$userId])->first();
            $dataqty=$cart->qty;
            $cartid=$cart->id;
            $newqty=$qty+$dataqty;
            DB::update('update cart set qty=?  WHERE id = ? and user_id=?',[$newqty,$cartid,$userId]);
        }else{
            DB::table('cart')->insert([
            'user_id'=>$userId,
            'product_id'=>$id,
            'qty'=>$qty,
        ]);
        }
        

        return redirect('attendant/dash');
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function namesearch($name)
    {
       
        $data=session()->get('attendant');
        $userId=$data['id'];
        $named="%".$name."%";
        $products=DB::table('Products')->whereRaw("Product_name LIKE ?",[$named])->get();
        
        if ((DB::table('Products')->whereRaw("Product_name LIKE ?",[$named])->count())==0) {
            return "<div class='errors text-center'>No product found</div>";
            
        }else {
            $table=
            "    <div class='table-responsive' >
            <table class='table table-striped table-sm text-left'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Instock</th>
                        <th>Qty &nbsp &nbsp   Add</th>
                    </tr>
                </thead>
                <tbody>";
                
                        foreach($products as $good){
                            $tot=$good->unit_cost+$good->VAT;
                        $table.=   "
                                <tr>
                                    
                                        <td>$good->Product_name</td>
                                        <td>$tot</td>
                                        <td>$good->instock</td>
                                        <td>
                                            
                                    
                                            <form action='/attendant/handler' class='cartadd' method='POST'>
                                                <input type='number' min='1' value='1' class='qty' name='qty'> &nbsp
                                                <input type='hidden' name='prod_id' value='$good->ID'>
                                                <button type='submit' class='btn btn-primary subtocart' value='submit'>
                                                    <i class='fas fa-plus'></i>
                                                </button>
                                            </form>
                                        </td>
                                    
                                </tr>
                            ";
                        }

                        $table.="
                        </tbody>
            </table>
        </div>"
            ;
            
            return $table;
        
        } 
        
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function checkout()
    {   
        $cart_content=DB::table('cart')->join('products','products.ID','=','cart.product_id')
        ->get();
        $cost=0;
        foreach ($cart_content as $product ) {
            $unitcost=$product->unit_cost +$product->VAT;
            $total=$unitcost*$product->qty;
            $cost+=$total;
        }
        if (session()->exists('details')) {
            $details=session()->get('details');
        }
        $paymethod=request('paymethod');
        $now=Carbon::now()->toDateTimeString();
        $time=strtotime('now');
        $year=date('Y',$time);
        $month=date('F',$time);
        $day=date('d',$time);
        $data=session()->get('attendant');
        $userId=$data['id'];
        DB::table('invoices')->insert([
            'Time'=>$time,
            'pay_methodid'=>$paymethod,
            'Month'=>$month,
            'Year'=>$year,
            'Day'=>$day,
            'Timestamp'=>$now,
            'UsersID'=>$userId,
            'Totalcost'=>$cost,
            'currency_id'=>$details->Currency_id,
        ]);
        $invoice=DB::table('invoices')->where('Time',$time)->first();
        $invoiceId=$invoice->ID;
        $items=DB::table('cart')->where('user_id',$userId)->get();
        foreach ($items as $item ) {
            $productId=$item->product_id;
            $product=DB::table('products')->where('ID',$productId)->first();
            $oldqty=$product->instock;
            $newqty=$oldqty-$item->qty;
            DB::table('invoiceitem')->insert([
                'invoicesID'=>$invoiceId,
                'ProductsID'=>$item->product_id,
                'Quantity'=>$item->qty,
            ]);
            DB::update('update products set instock=?  WHERE ID = ? ',[$newqty,$productId]);
        }
        DB::delete('delete from cart where user_id=?',[$userId]);
        return $this->receipt();
        }
    

          //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function receipt()
    {   
        
        $data=session()->get('attendant');
        $userId=$data['id'];
        $invoice=DB::table('invoices')
        ->join('pay_methods','pay_methods.id','=','invoices.pay_methodid')
        ->join('users','users.User_ID','=','invoices.UsersID')
        ->where('UsersID',$userId)->OrderBY('Time','desc')->first();
        $items=DB::table('invoiceitem')->join('products','products.ID','=','invoiceitem.ProductsID')
        ->where('InvoicesID',$invoice->ID)->get();
        
        return view('attendant/receipt',[
            'invoice'=>$invoice,
            'items'=>$items
        ]);
        }


     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function getsales()
    {
        if (!session()->exists('admin')) {
            return redirect('login');
        }else {
            //get categories from DB
            $years=DB::select(" select Distinct Year from invoices Order By Year desc");
            $sales=DB::table('invoices')
            ->join('users','users.User_ID','=','invoices.UsersID')
            ->join('currency','currency.id','=','invoices.currency_id')
            ->OrderBY('Time','desc')
            ->get();
            foreach ($sales as $sale ) {
                $item[$sale->ID]=DB::table('invoiceitem')
                ->join('Products','Products.ID','=','invoiceitem.ProductsID')
                ->where('InvoicesID',$sale->ID)->get();
            }
            
            //return the products view along with the product data
           return view('Administrator/sales',[
               'sales'=>$sales,
               'item'=>$item,
               'years'=>$years
           ]);  
        }   
       
        }


        
     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function getmysales()
    {
        if (!session()->exists('attendant')) {
            return redirect('login');
        }else {
            $data=session()->get('attendant');
            $userId=$data['id'];
            $now=strtotime('now');
            $today=date('d',$now);
            $thismonth=date('F',$now);
            $thisyear=date('Y',$now);
            //get categories from DB
            $mysalecount=DB::table('invoices')->whereRaw('UsersID=?',[$userId])->count();
            $mysalesTM=DB::table('invoices')->whereRaw('Month=? and Year=? and UsersID=?',[$thismonth,$thisyear,$userId])->count();
            $mysalesT=DB::table('invoices')->whereRaw('Day=? and Month=? and Year=? and UsersID=?',[$today,$thismonth,$thisyear,$userId])->count();
            $salescount=DB::table('invoices')
            ->join('users','users.User_ID','=','invoices.UsersID')
            ->join('currency','currency.id','=','invoices.currency_id')
            ->whereRaw('UsersID=?',[$userId])
            ->OrderBY('Time','desc')
            ->count();
            if ($salescount>0) {
                $sales=DB::table('invoices')
                ->join('users','users.User_ID','=','invoices.UsersID')
                ->join('currency','currency.id','=','invoices.currency_id')
                ->whereRaw('UsersID=?',[$userId])
                ->OrderBY('Time','desc')
                ->get();
                foreach ($sales as $sale ) {
                    $item[$sale->ID]=DB::table('invoiceitem')
                    ->join('Products','Products.ID','=','invoiceitem.ProductsID')
                    ->where('InvoicesID',$sale->ID)->get();
                }
                
                //return the products view along with the product data
            return view('Attendant/mysales',[
                'sales'=>$sales,
                'item'=>$item,
                'mysalesTM'=>$mysalesTM,
                'mysalesT'=>$mysalesT,
                'salecount'=>$mysalecount,
            ]);  
            }else {
                return view('Attendant/mysales');
            }
            
        }   
       
        }
         //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function addcategory()
    {
        $data= request()-> validate([
            
            'categoryname'=>'required',
        ]);
            $categoryname=request('categoryname');

            DB::table('categories')->insert([
                'category'=>$categoryname,
            ]);
            return redirect()->back()->with('success','Category has been successfully added');

        }

        
         //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function filteryear()
    {
        $data= request()-> validate([
            
            'yearfilter'=>'required',
        ]);
            $yearfilter=request('yearfilter');
            $years=DB::select(" select Distinct Year from invoices Order By Year desc");
            $months=DB::select(" select Distinct Month from invoices where year=?",[$yearfilter]);
            $sales=DB::table('invoices')
            ->join('users','users.User_ID','=','invoices.UsersID')
            ->join('currency','currency.id','=','invoices.currency_id')
            ->where('Year',$yearfilter)
            ->OrderBY('Time','desc')
            ->get();
            foreach ($sales as $sale ) {
                $item[$sale->ID]=DB::table('invoiceitem')
                ->join('Products','Products.ID','=','invoiceitem.ProductsID')
                ->where('InvoicesID',$sale->ID)->get();
            }
            
            //return the products view along with the product data
           return view('Administrator/sales',[
               'sales'=>$sales,
               'item'=>$item,
               'years'=>$years,
               'yearfilter'=>$yearfilter,
               'months'=>$months,
           ]);  
        }  
        

             
         //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function filtermonth()
    {
        $data= request()-> validate([
            
            'monthfilter'=>'required',
            'month_year'=>'required',
        ]);
            $monthfilter=request('monthfilter');
            $yearfilter=request('month_year');
            $years=DB::select(" select Distinct Year from invoices Order By Year desc");
            $months=DB::select(" select Distinct Month from invoices where year=?",[$yearfilter]);
            $days=DB::select(" select Distinct Day from invoices where year=? and Month=?",[$yearfilter,$monthfilter]);
            $sales=DB::table('invoices')
            ->join('users','users.User_ID','=','invoices.UsersID')
            ->join('currency','currency.id','=','invoices.currency_id')
            ->whereRaw('Year=? and Month=?',[$yearfilter,$monthfilter])
            ->OrderBY('Time','desc')
            ->get();
            foreach ($sales as $sale ) {
                $item[$sale->ID]=DB::table('invoiceitem')
                ->join('Products','Products.ID','=','invoiceitem.ProductsID')
                ->where('InvoicesID',$sale->ID)->get();
            }
            
            //return the products view along with the product data
           return view('Administrator/sales',[
               'sales'=>$sales,
               'item'=>$item,
               'years'=>$years,
               'yearfilter'=>$yearfilter,
               'monthfilter'=>$monthfilter,
               'months'=>$months,
               'days'=>$days,
           ]);  
        }  

              
         //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function filterday()
    {
        $data= request()-> validate([
            
            'dayfilter'=>'required',
            'day_month'=>'required',
            'day_year'=>'required',
        ]);
            $dayfilter=request('dayfilter');
            $monthfilter=request('day_month');
            $yearfilter=request('day_year');
            $years=DB::select(" select Distinct Year from invoices Order By Year desc");
            $months=DB::select(" select Distinct Month from invoices where year=?",[$yearfilter]);
            $days=DB::select(" select Distinct Day from invoices where year=? and Month=?",[$yearfilter,$monthfilter]);
            $sales=DB::table('invoices')
            ->join('users','users.User_ID','=','invoices.UsersID')
            ->join('currency','currency.id','=','invoices.currency_id')
            ->whereRaw('Year=? and Month=? and day=?',[$yearfilter,$monthfilter,$dayfilter])
            ->OrderBY('Time','desc')
            ->get();
            foreach ($sales as $sale ) {
                $item[$sale->ID]=DB::table('invoiceitem')
                ->join('Products','Products.ID','=','invoiceitem.ProductsID')
                ->where('InvoicesID',$sale->ID)->get();
            }
            
            //return the products view along with the product data
           return view('Administrator/sales',[
               'sales'=>$sales,
               'item'=>$item,
               'years'=>$years,
               'yearfilter'=>$yearfilter,
               'monthfilter'=>$monthfilter,
               'dayfilter'=>$dayfilter,
               'months'=>$months,
               'days'=>$days,
           ]);  
        }  

    
        
             //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
        public function getstats()
        {   
            
            $now=strtotime('now');
            $today=date('d',$now);
            $thismonth=date('F',$now);
            $thisyear=date('Y',$now);
            $mysalecount;
            $mysalesTM;
            $mysalesT;
            $salesmade;
            $attendants=DB::table('users')->where('UserTypesID','3')->get();
            foreach ($attendants as $attendant ) {
                $userId=$attendant->User_ID;
                $mysalecount[$attendant->User_ID]=DB::table('invoices')->whereRaw('UsersID=?',[$userId])->count();
                $salesmade[$attendant->User_ID]=DB::table('invoices')->whereRaw('UsersID=?',[$userId])->sum('Totalcost');
                $mysalesTM[$attendant->User_ID]=DB::table('invoices')->whereRaw('Month=? and Year=? and UsersID=?',[$thismonth,$thisyear,$userId])->count();
                $mysalesT[$attendant->User_ID]=DB::table('invoices')->whereRaw('Day=? and Month=? and Year=? and UsersID=?',[$today,$thismonth,$thisyear,$userId])->count();
                
            }
            
           
            
            //return the products view along with the product data
           return view('Administrator/statistics',[
               'attendants'=>$attendants,
               'mysalesTM'=>$mysalesTM,
               'mysalesT'=>$mysalesT,
               'salecount'=>$mysalecount,
               'salesmade'=>$salesmade
           ]); 

        }
}
