<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new \App\Category();
        $product->name = 'Electronics';
        $product->lft = 1;
        $product->rgt = 20;
        $product->save();

        $product = new \App\Category();
        $product->name = 'Televisions';
        $product->lft = 2;
        $product->rgt = 9;
        $product->save();

        $product = new \App\Category();
        $product->name = 'Tube';
        $product->lft = 3;
        $product->rgt = 4;
        $product->save();

        $product = new \App\Category();
        $product->name = 'LCD';
        $product->lft = 5;
        $product->rgt = 6;
        $product->save();

        $product = new \App\Category();
        $product->name = 'Plasma';
        $product->lft = 7;
        $product->rgt = 8;
        $product->save();

        $product = new \App\Category();
        $product->name = 'Portable Electronics';
        $product->lft = 10;
        $product->rgt = 19;
        $product->save();

        $product = new \App\Category();
        $product->name = 'MP3 Players';
        $product->lft = 11;
        $product->rgt = 14;
        $product->save();

        $product = new \App\Category();
        $product->name = 'Flash';
        $product->lft = 12;
        $product->rgt = 13;
        $product->save();

        $product = new \App\Category();
        $product->name = 'CD Players';
        $product->lft = 15;
        $product->rgt = 16;
        $product->save();

        $product = new \App\Category();
        $product->name = '2 WAY Radios';
        $product->lft = 17;
        $product->rgt = 18;
        $product->save();
    }
}
