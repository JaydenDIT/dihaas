<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Library\Database\AutoIncrement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            ['role_id' => 1, 'role_name' => 'HOD Assistant', 'role_group' => 'single_department'],
            ['role_id' => 2, 'role_name' => 'HOD', 'role_group' => 'single_department'],
            ['role_id' => 3, 'role_name' => 'AD Assistant', 'role_group' => 'all_department'],
            ['role_id' => 4, 'role_name' => 'AD Nodal', 'role_group' => 'all_department'],
            ['role_id' => 5, 'role_name' => 'DP Assistant', 'role_group' => 'all_department'],
            ['role_id' => 6, 'role_name' => 'DP Nodal', 'role_group' => 'all_department'],
            ['role_id' => 7, 'role_name' => 'DP Signing Authority', 'role_group' => 'all_department'],
            ['role_id' => 8, 'role_name' => 'Department Signing Authority', 'role_group' => 'single_department'],
            ['role_id' => 77, 'role_name' => 'Citizen', 'role_group' => 'citizen'],
            ['role_id' => 999, 'role_name' => 'Superadmin', 'role_group' => 'superadmin'],
        ];

        DB::table('roles')->upsert(
            $roles,
            ['role_id'], // unique constraint to check existing
            ['role_name', 'role_group']  // columns to update if exists
        );

        // Reset sequence/auto increment for portability
        AutoIncrement::resetIndex('roles', 'role_id');
    }
}
