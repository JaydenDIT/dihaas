<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::insert("insert into roles (role_id, role_name) values  
                    (1, 'HOD Assistant'),       
                    (2, 'HOD'),
                    (3, 'AD Assistant'),
                    (4, 'AD Nodal'),
                    (5, 'DP Assistant'),
                    (6, 'DP Nodal'),
                    (77, 'Citizen'),
                    (999, 'Superadmin'),
                    (8, 'DP Signing Authority'),
                    (9, 'Department Signing Authority')
        ");
    }
}
