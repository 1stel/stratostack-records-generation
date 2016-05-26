<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UsersSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(['name'     => 'Admin',
                      'email'    => 'admin',
                      'password' => bcrypt('admin'),
                      'role'     => 'Admin'
        ]);
    }

}