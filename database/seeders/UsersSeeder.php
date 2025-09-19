<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        By default we assume that there are two user types one is super admin and other is a citizen user. Once
        loginned, user can change email, fullname, mobile ... anything.
        */
        $defaultUsers = [
            ['fullname' => 'System Administrator', 'mobile' => '9999999999', 'email' => 'admin@gmail.com', 'role_id' => 999, 'password' => Hash::make('admin@123')], //super-admin
            ['fullname' => 'Citizen user', 'mobile' => '9999999999', 'email' => 'leecba@gmail.com', 'role_id' => 77, 'password' => Hash::make('Test@123')], //citizen
        ];

        User::upsert(
            $defaultUsers,
            ['email'],
            ['fullname', 'mobile', 'role_id', 'password']
        );
    }
}
