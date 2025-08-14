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
        DB::insert("insert into roles (role_id, role_name, role_group) values  
                    (1, 'HOD Assistant', 'single_department'),       
                    (2, 'HOD', 'single_department'),
                    (3, 'AD Assistant', 'all_department'),
                    (4, 'AD Nodal', 'all_department'),
                    (5, 'DP Assistant', 'all_department'),
                    (6, 'DP Nodal', 'all_department'),
                    (8, 'DP Signing Authority', 'all_department'),
                    (9, 'Department Signing Authority', 'single_department'),
                    (77, 'Citizen', 'citizen'),
                    (999, 'Superadmin', 'superadmin')
        ");
    }
}
