<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['state_id' => 1, 'district_name' => 'Bishnupur'],
            ['state_id' => 1, 'district_name' => 'Chandel'],
            ['state_id' => 1, 'district_name' => 'Churachandpur'],
            ['state_id' => 1, 'district_name' => 'Imphal East'],
            ['state_id' => 1, 'district_name' => 'Imphal West'],
            ['state_id' => 1, 'district_name' => 'Jiribam'],
            ['state_id' => 1, 'district_name' => 'Kakching'],
            ['state_id' => 1, 'district_name' => 'Kamjong'],
            ['state_id' => 1, 'district_name' => 'Kangpokpi'],
            ['state_id' => 1, 'district_name' => 'Noney'],
            ['state_id' => 1, 'district_name' => 'Pherzawl'],
            ['state_id' => 1, 'district_name' => 'Senapati'],
            ['state_id' => 1, 'district_name' => 'Tamenglong'],
            ['state_id' => 1, 'district_name' => 'Tengnoupal'],
            ['state_id' => 1, 'district_name' => 'Thoubal'],
            ['state_id' => 1, 'district_name' => 'Ukhrul'],
            ['state_id' => 2, 'district_name' => 'Aizawl'],
            ['state_id' => 2, 'district_name' => 'Champhai'],
            ['state_id' => 2, 'district_name' => 'Hnahthial'],
            ['state_id' => 2, 'district_name' => 'Kolasib'],
            ['state_id' => 2, 'district_name' => 'Lawngtlai'],
            ['state_id' => 2, 'district_name' => 'Lunglei'],
            ['state_id' => 2, 'district_name' => 'Mamit'],
            ['state_id' => 2, 'district_name' => 'Saiha'],
            ['state_id' => 2, 'district_name' => 'Serchhip'],
            ['state_id' => 3, 'district_name' => 'Dimapur'],
            ['state_id' => 3, 'district_name' => 'Kiphire'],
            ['state_id' => 3, 'district_name' => 'Longleng'],
            ['state_id' => 3, 'district_name' => 'Mokokchung'],
            ['state_id' => 3, 'district_name' => 'Mon'],
            ['state_id' => 3, 'district_name' => 'Peren'],
            ['state_id' => 3, 'district_name' => 'Phek'],
            ['state_id' => 3, 'district_name' => 'Tuensang'],
            ['state_id' => 3, 'district_name' => 'Wokha'],
            ['state_id' => 3, 'district_name' => 'Zunheboto'],
        ];

        DB::table('districts')->upsert(
            $districts,
            ['district_name'], // unique column for check
            ['state_id']       // update state_id if district already exists
        );
    }
}
