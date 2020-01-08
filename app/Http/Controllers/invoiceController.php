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
    //The function to load admin dashboard
    public function index()
    {
        //check if an admin is logged in
        if (!session()->exists('admin')) {
            //if it's not redirect to login page
            return redirect('login');
        }else {
            //get data from database
            $tomorrow=strtotime('tomorrow 00:00');
            $today = strtotime('today 00:00');
        
            $userNum=DB::table('users')->count();
            $productNum=DB::table('products')->count();
            $categoryNum=DB::table('categories')->count();
            $reorderNum=DB::table('products')->whereRaw('Reorder_level > instock')->count();
            $todaySales=DB::table('invoices')->whereRaw('?<=Time and Time<=?',[$today,$tomorrow])->count();
            $todayCash=DB::table('invoices')->whereRaw('?<=Time and Time<=?',[$today,$tomorrow])->sum('Totalcost');
            
           //return the dashboard view and send the data from database
            return view('Administrator/dash',[
                'userNum'=>$userNum,
                'productNum'=>$productNum,
                'categoryNum'=>$categoryNum,
                'reorderNum'=>$reorderNum,
                'todaySales'=>$todaySales,
                'todayCash'=>$todayCash,
            ]);
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to get usertypes from database for add user page
   public function usertypes()
   {
       //check if an admin is logged in
    if (!session()->exists('admin')) {
        return redirect('login');
    }else {
        //get usertypes
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
     if (!session()->exists('admin')) {
         return redirect('login');
     }else {
         //get categories
         $categories=DB::table('categories')->get();
         //return the add user view
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
    if (!session()->exists('admin')) {
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
    if (!session()->exists('admin')) {
        return redirect('login');
    }else {
        //get the users data from DB
         $users=DB::table('users')->join('usertypes','users.UserTypesID','=','UserTypes.ID')
         ->get();
        

       return view('Administrator/users',[
           'users'=>$users,
       ]);
       
    }

   }

   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to delete user from database 
   public function deleteUser($id) 
   {
    if (!session()->exists('admin')) {
        return redirect('login');
    }else {
        DB::delete('delete from users where User_Id = ?',[$id]);
        return redirect()->back()->with('success','User has been successfully Deleted');
    
    }
    }

      //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to delete product from database 
   public function deleteProduct($id) 
   {
    if (!session()->exists('admin')) {
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
    if (!session()->exists('admin')) {
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
    if (!session()->exists('admin')) {
        return redirect('login');
    }else {
        Session::put('url.intended',URL::previous());
        //get usertypes
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
     if (!session()->exists('admin')) {
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
     if (!session()->exists('admin')) {
         return redirect('login');
     }else {
         //get the users data from DB
         
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
        DB::update('update company set Company_name=?,Currency_id=?,Phone_no=?,Email=?,Address=?  where ID=?',[$companyname,$currency,$phone,$email,$address,1]);

 
        return redirect('/admin/dash');
        
     }
 
    }

   //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to add users to database 
   public function adduser()
   {
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

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to Edit users in database 
    public function editUser($id)
    {   
       
        
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
 
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to add products to database 
    public function addproduct()
   {
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

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to add products to database 
    public function editProduct($id)
   {
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

    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //function to login 
   public function login()
   {
    

        $time=Carbon::now()->toDateTimeString();
        
        
       session()->flush();
       $data=request()->validate([
           
           'password'=>'required',
           'username'=>'required|exists:users,username',
       ]);
        
       
       $username=request('username');
       $password=request('password');
       $db_passer=DB::table('users')->select('password','UserTypesID')->where('username',$username)->first();
       $db_pass=$db_passer->password;
       $usertypeId=$db_passer->UserTypesID;       
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

   
   public function logout()
    {
        session()->flush();
        return redirect('login');
    }
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
