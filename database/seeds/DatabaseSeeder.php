<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        for ($i=0; $i < 300; $i++) {
            $fullprice= (50*rand(1,50));
            $fullprice=number_format((float)$fullprice, 2, '.', '');
            $vat=(mt_rand(10,20)/10);
            $vat=number_format((float)$vat, 2, '.', '');
            $price=$fullprice-$vat;
            $price=number_format((float)$price, 2, '.', '');
            DB::table('Products')->insert([
            'categoriesID'=>rand(1,19),
            'Product_name'=>str::random(10),
            'unit_cost'=>$price,
            'VAT'=>$vat,
            'instock'=>rand(100,200),
            'Reorder_level'=>20*rand(1,4),
        ]);
        }
        
    }
}
