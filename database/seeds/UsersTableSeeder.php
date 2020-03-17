<?php

use App\Outlet;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'role' => 0
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'role' => 0
        ]);
        User::create([
            'name' => 'Finance',
            'email' => 'finance@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'role' => 0
        ]);
        $outlet = Outlet::create([
            'code' =>'KT6',
            'name'=>'Jaya Raya',
            'status'=>'Jalan Mojo lembah rt 3 rw 2',
            'phone'=>'087721163128'
        ]);
        User::create([
            'name' => 'kurir',
            'email' => 'kurir@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'role' => 0,
            'outlet_id'=> $outlet->id
        ]);
    }
}
