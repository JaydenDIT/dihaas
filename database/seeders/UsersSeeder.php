<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::insert("insert into users (fullname, mobile, email, role_id, password) values  
        ('Administrator', '9999999999', 'lkonsam@gmail.com', 999, '" . Hash::make('admin@123') . "'),
        ('Tomba Singh', '9111111111', 'leecba@gmail.com', 77, '" . Hash::make('12345678') . "')
    ");
    }
}
