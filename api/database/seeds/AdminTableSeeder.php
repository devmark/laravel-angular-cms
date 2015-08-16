<?php

use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{

    public function run()
    {
        //DB::table('admin')->truncate();
        Admin::create(['firstname' => 'Mark', 'email' => 'admin@emcoo.com', 'password' => Hash::make('adminmark'), 'last_login' => date('Y-m-d H:i:s')]);
    }

}