<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new \App\Product();
        $product->name = 'Hannah Frank télikabát';
        $product->price = '29900';
        $product->quantity = 10;
        $product->description = 'text...';
        $product->attributes = 'text...';
        $product->save();
    }
}
