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
        //
        DB::insert("insert into districts (state_id, district_name) values 
        (1, 'Bishnupur'),
        (1,  'Chandel'),
        (1,  'Churachandpur'),
        (1,  'Imphal East'),
        (1,  'Imphal West'),
        (1,  'Jiribam'),
        (1,  'Kakching'),
        (1,  'Kamjong'),
        (1,  'Kangpokpi'),
        (1,  'Noney'),
        (1,  'Pherzawl'),
        (1,  'Senapati'),
        (1,  'Tamenglong'),
        (1,  'Tengnoupal'),
        (1,  'Thoubal'),
        (1,  'Ukhrul'),
        (2,  'Aizawl'),
        (2,  'Champhai'),
        (2,  'Hnahthial'),
        (2,  'Kolasib'),
        (2,  'Lawngtlai'),
        (2,  'Lunglei'),
        (2,  'Mamit'),
        (2,  'Saiha'),
        (2,  'Serchhip'),
        (3,  'Dimapur'),
        (3,  'Kiphire'),
        (3,  'Longleng'),
        (3,  'Mokokchung'),
        (3,  'Mon'),
        (3,  'Peren'),
        (3,  'Phek'),
        (3,  'Tuensang'),
        (3,  'Wokha'),
        (3,  'Zunheboto')
    ");
    }
}
