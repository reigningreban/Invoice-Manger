<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;
use URL;
class invoiceController extends Controller
{
    //Function to check if an admin is logged in
    public function getadminlog()
    {
        if (!session()->exists('admin')) {
            return false;
        }else {
            return true;
        }   
    }
    //The function to load admin dashboard
    public function index()
    {
        //check if an admin is logged in
        $logged=$this->getadminlog();
        if (!$logged) {
            //if it's not redirect to login page
            return redirect('login');
        }else {
            //get data from database for the admin dashboard
            $tomorrow=strtotime('tomorrow 00:00');
            $today = strtotime('today 00:00');
        
            $userNum=DB::table('users')->count();
            $productNum=DB::table('products')->count();
            $categoryNum=DB::table('categories')->count();
            $reorderNum=DB::table('products')->whereRaw('Reorder_level > instock')->count();
            $todaySales=DB::table('invoices')->whereRaw('?<=Time and Time<=?',[$today,$tomorrow])->count();
            $todayCash=DB::table('invoices')->whereRaw('?<=Time and Time<=?',[$today,$tomorrow])->sum('Totalcost');
            // ten most recent sales
            $sales=DB::table('invoices')
            ->join('users','users.User_ID','=','invoices.UsersID')
            ->join('currency','currency.id','=','invoices.currency_id')
            ->OrderBY('Time','desc')
            ->limit(10)
            ->get();
            //getting the items in the sale
            foreach ($sales as $sale ) {
                $item[$sale->ID]=DB::table('invoiceitem')
                ->join('Products','Products.ID','=','invoiceitem.ProductsID')
                ->where('InvoicesID',$sale->ID)->get();
            }
           //return the dashboard view and send the data from database
            return view('Administrator/dash',[
                'userNum'=>$userNum,
                'productNum'=>$productNum,
                'categoryNum'=>$categoryNum,
                'reorderNum'=>$reorderNum,
                'todaySales'=>$todaySales,
                'todayCash'=>$todayCash,
                'sales'=>$sales,
                'item'=>$item,
            ]);
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get usertypes from database for add user page
   public function usertypes()
   {
       //check if an admin is logged in
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        //get the usertypes
        $types=DB::table('usertypes')->get();
        //return the add user view
       return view('Administrator/adduser',[
           'types'=>$types
       ]);
    } 
   }
   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get categories from database for add products page
    public function categories()
    {
        //check if an admin is logged in
        $logged=$this->getadminlog();
     if (!$logged) {
         return redirect('login');
     }else {
         //get categories
         $categories=DB::table('categories')->get();
         //return the add products view
        return view('Administrator/addproduct',[
            'categories'=>$categories
        ]);
     } 
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get products from database for products page
   public function products()
   {
       //You should know this by now
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        //get categories from DB
         $categories=DB::table('categories')->orderBy('id')->get();
        foreach ($categories as $num) {
            //use the category id to get the products from each category
            $products[$num->ID]=DB::table('products')->where('categoriesID',$num->ID)->get();
            
        }
        //return the products view along with the product data
       return view('Administrator/products',[
           'categories'=>$categories,
           'products'=>$products,
       ]);  
    }   
   }

   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get users from database for users page
   public function users()
   {    
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        //get the users data from DB
         $users=DB::table('users')->join('usertypes','users.UserTypesID','=','UserTypes.ID')
         ->get();
        
        //return the users view
       return view('Administrator/users',[
           'users'=>$users,
       ]);
       
    }

   }

   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to delete user from database 
   public function deleteUser($id) 
   {
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        //Id comes from the webpage
        DB::delete('delete from users where User_Id = ?',[$id]);
        return redirect()->back()->with('success','User has been successfully Deleted');
    
    }
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to delete product from database 
   public function deleteProduct($id) 
   {
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        DB::delete('delete from products where ID = ?',[$id]);
        return redirect()->back()->with('success','Product has been successfully Deleted');
    
    }
    }

     //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to show user for editting 
   public function showUser($id) 
   {
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        //get usertypes
        $user=DB::table('users')->where('User_ID','=',$id)->first();
        $types=DB::table('usertypes')->get();
        
        return view('Administrator/edituser',[
            'user'=>$user,
            'types'=>$types
        ]);
    } 
    }   
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to show product for editting 
   public function showProduct($id) 
   {
       $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        Session::put('url.intended',URL::previous());
        //get categories
        $product=DB::table('Products')->where('ID','=',$id)->first();
        $categories=DB::table('categories')->get();
        
        return view('Administrator/editproduct',[
            'product'=>$product,
            'categories'=>$categories
        ]);
    } 
    
    
    
    }
   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get required restock products from database for restock page
    public function restock()
    {
        $logged=$this->getadminlog();
     if (!$logged) {
         return redirect('login');
     }else {
         //get the users data from DB
         $reorder=DB::table('products')->join('categories','products.CategoriesID','=','categories.ID')
         ->select('products.*','categories.category')
         ->whereRaw('products.Reorder_level > products.instock')
          ->get();
         
 
        return view('Administrator/restock',[
            'reorder'=>$reorder,
        ]);
        
     }
 
    }

//-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get required restock products from database for restock page
    public function editcompany()
    {
        $logged=$this->getadminlog();
     if (!$logged) {
         return redirect('login');
     }else {
         
        //validating the company data
         
         $data= request()-> validate([
            'company_name'=>'required',
            'currency'=>'required',
            'company_phone'=>'required',
            'company_email'=>'required|email:rfc,dns',
            'company_address'=>'required',
            
        ]);
        $companyname=request('company_name');
        $currency=request('currency');
        $phone=request('company_phone');
        $address=request('company_address');
        $email=request('company_email');
        //updating the database
        DB::update('update company set Company_name=?,Currency_id=?,Phone_no=?,Email=?,Address=?  where ID=?',[$companyname,$currency,$phone,$email,$address,1]);

 
        return redirect('/admin/dash');
        
     }
 
    }

   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to add users to database 
   public function adduser()
   {
    $logged=$this->getadminlog();
    if (!$logged) {
        return redirect('login');
    }else {
        //form validation
        $data= request()-> validate([
            'usertype'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'username'=>'bail|required|unique:users,username',
            'pword'=>'bail|required',
            'pass'=>'bail|required_with:pword|same:pword',
        ]);

        //get form data
        $firstname=request('firstname');
        $lastname=request('lastname');
        $username=request('username');
        $pword=request('pword');
        $type=request('usertype');
        $pass= Hash::make($pword);

        //if validation is successful, write data into DB
        DB::table('users')->insert([
            'UserTypesID'=>$type,
            'firstname'=>$firstname,
            'lastname'=>$lastname,
            'username'=>$username,
            'password'=>$pass
        ]);
        //return success statement
        return redirect()->back()->with('success','User has been successfully added');
    }
       
   }

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to Edit users in database 
    public function editUser($id)
    {   
        $logged=$this->getadminlog();
        if (!$logged) {
            return redirect('login');
        }else {
                //form validation
                $data= request()-> validate([
                    'usertype'=>'required',
                    'firstname'=>'required',
                    'lastname'=>'required',
                    'username'=>'bail|required|unique:users,username,'.$id.',User_ID',
                    
                    
                ]);
                
        
                //get form data
                
                $firstname=request('firstname');
                $lastname=request('lastname');
                $username=request('username');
                if (null !==request('pword')) {
                    $pword=request('pword');
                    $pass= Hash::make($pword);
                }else {
                    $password=DB::table('users')->select('password')->where('User_ID','=',$id)->first();
                    $pass=$password->password;
                }
                
                $type=request('usertype');
                
        
                //if validation is successful, write data into DB
                DB::update('update users set UserTypesID=?, Firstname = ?, Lastname=?, Username=?, password=?  WHERE User_Id = ?',[$type,$firstname,$lastname,$username,$pass,$id]);
                
                //return success statement
                return redirect('admin/users')->with('success','User has been successfully Edited');
        }
        
        
    }
 
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to add products to database 
    public function addproduct()
   {
        $logged=$this->getadminlog();
        if (!$logged) {
            return redirect('login');
        }else {
                //form validation
                $data= request()-> validate([
                    'name'=>'required',
                    'cost'=>'required',
                    'vat'=>'required',
                    'stock'=>'required',
                    'reorder'=>'required',
                    'category'=>'required',
                ]);

                //get form data
                $name=request('name');
                $cost=request('cost');
                $vat=request('vat');
                $stock=request('stock');
                $reorder=request('reorder');
                $category=request('category');

                //if validation is successful, write data into DB
                DB::table('products')->insert([
                    'CategoriesID'=>$category,
                    'product_name'=>$name,
                    'unit_cost'=>$cost,
                    'VAT'=>$vat,
                    'instock'=>$stock,
                    'Reorder_level'=>$reorder
                ]);
                //return success statement
                return redirect()->back()->with('success','Product has been successfully added');
        }
       
   }

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to add products to database 
    public function editProduct($id)
   {
            $logged=$this->getadminlog();
        if (!$logged) {
            return redirect('login');
        }else {
                 //form validation
                $data= request()-> validate([
                    'name'=>'required',
                    'cost'=>'required',
                    'vat'=>'required',
                    'stock'=>'required',
                    'reorder'=>'required',
                    'category'=>'required',
                ]);

                //get form data
                $name=request('name');
                $cost=request('cost');
                $vat=request('vat');
                $stock=request('stock');
                $reorder=request('reorder');
                $category=request('category');

                //if validation is successful, write data into DB
                DB::update('update products set CategoriesID=?, Product_name = ?, unit_cost=?, VAT=?, instock=?,Reorder_level=?  WHERE ID = ?',[$category,$name,$cost,$vat,$stock,$reorder,$id]);
                
                //return success statement
                return redirect(Session::get('url.intended'))->with('success','Product has been successfully Edited');
        }
       
   }

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to login 
   public function login()
   {
    

        $time=Carbon::now()->toDateTimeString();
        
        //logout anyone logged in
       session()->flush();
       //form validation
       $data=request()->validate([
           
           'password'=>'required',
           'username'=>'required|exists:users,username',
       ]);
        
       
       $username=request('username');
       $password=request('password');
       //if the user exists fetch the password
       $db_passer=DB::table('users')->select('password','UserTypesID')->where('username',$username)->first();
       $db_pass=$db_passer->password;
       $usertypeId=$db_passer->UserTypesID; 
       //compare passwords      
       $check=Hash::check($password,$db_pass);
       if ($check) {
        $usertyper=DB::table('Users')
        ->join('Usertypes','UserTypes.id','=','users.UserTypesID')
        ->select('userTypes.Usertype','users.User_ID')
        ->where('username',$username)->first();
        $usertype=$usertyper->Usertype;
        $userId=$usertyper->User_ID;
        $company=DB::table('company')->join('currency','company.Currency_id','=','currency.id')
        ->first();
       session()->put('details',$company);
           $result=[
               'id'=>$userId,
               'username'=>$username,
               'password'=>$password,
               'usertypeId'=>$usertypeId,
               'time'=>$time,
               
           ];
           if ($usertypeId==1) {
                session()->put('admin',$result);
                return redirect('admin/dash');
           }elseif ($usertypeId==2) {
            session()->put('admin',$result);
            return redirect('admin/dash');
           }elseif ($usertypeId==3) {
            session()->put('attendant',$result);
            return redirect('attendant');
           }
           
            
            
       }else {
           return redirect()->back()->with('pass_crash','Invalid Username or Password')->withInput();
       }
   }

   //logout function
   public function logout()
    {
        session()->flush();
        return redirect('login');
    }
    // autologout
    public function autologout()
    {
        if (session()->exists('admin')) {
            $data=session()->get('admin');
            $username=$data['username'];
            session()->flush();
            $results=[
                'username'=>$username,
                'message'=>'You have been logged out due to inactivity',
            ];
            return redirect('login')->with('results',$results);
        
        }elseif (session()->exists('attendant')) {
            $data=session()->get('attendant');
            $username=$data['username'];
            session()->flush();
            $results=[
                'username'=>$username,
                'message'=>'You have been logged out due to inactivity',
            ];
            return redirect('login')->with('results',$results);
        }
        else{
            session()->flush();
            return redirect('login');
        }
       
    }
 
}
