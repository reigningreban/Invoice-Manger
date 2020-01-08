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
}
