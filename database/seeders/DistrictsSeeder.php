<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Library\Database\AutoIncrement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['district_id' => 1, 'state_id' => 1, 'district_name' => 'Bishnupur'],
            ['district_id' => 2, 'state_id' => 1, 'district_name' => 'Chandel'],
            ['district_id' => 3, 'state_id' => 1, 'district_name' => 'Churachandpur'],
            ['district_id' => 4, 'state_id' => 1, 'district_name' => 'Imphal East'],
            ['district_id' => 5, 'state_id' => 1, 'district_name' => 'Imphal West'],
            ['district_id' => 6, 'state_id' => 1, 'district_name' => 'Jiribam'],
            ['district_id' => 7, 'state_id' => 1, 'district_name' => 'Kakching'],
            ['district_id' => 8, 'state_id' => 1, 'district_name' => 'Kamjong'],
            ['district_id' => 9, 'state_id' => 1, 'district_name' => 'Kangpokpi'],
            ['district_id' => 10, 'state_id' => 1, 'district_name' => 'Noney'],
            ['district_id' => 11, 'state_id' => 1, 'district_name' => 'Pherzawl'],
            ['district_id' => 12, 'state_id' => 1, 'district_name' => 'Senapati'],
            ['district_id' => 13, 'state_id' => 1, 'district_name' => 'Tamenglong'],
            ['district_id' => 14, 'state_id' => 1, 'district_name' => 'Tengnoupal'],
            ['district_id' => 15, 'state_id' => 1, 'district_name' => 'Thoubal'],
            ['district_id' => 16, 'state_id' => 1, 'district_name' => 'Ukhrul'],
        ];

        DB::table('districts')->upsert(
            $districts,
            ['district_id'], // unique column for check
            ['state_id', 'district_name']       // update state_id if district already exists
        );

        // Reset sequence/auto increment for portability
        AutoIncrement::resetIndex('districts', 'district_id');
    }
}
