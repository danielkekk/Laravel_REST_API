<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->name = 'Daniel Kekk';
        $user->email = 'dani.kekk@gmail.com';
        $user->password = Hash::make('12345');
        $user->save();
    }
}
