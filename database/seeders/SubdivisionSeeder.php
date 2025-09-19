<?php

namespace Database\Seeders;

use App\Library\Database\AutoIncrement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubdivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subdivisions = [
            ['subdivision_id' => 1,  'district_id' => 1,  'subdivision_name' => 'Nambol'],
            ['subdivision_id' => 2,  'district_id' => 1,  'subdivision_name' => 'Moirang'],
            ['subdivision_id' => 3,  'district_id' => 1,  'subdivision_name' => 'Bishnupur'],
            ['subdivision_id' => 4,  'district_id' => 2,  'subdivision_name' => 'Chakpikarong'],
            ['subdivision_id' => 5,  'district_id' => 2,  'subdivision_name' => 'Khengjoy'],
            ['subdivision_id' => 6,  'district_id' => 2,  'subdivision_name' => 'Chandel'],
            ['subdivision_id' => 7,  'district_id' => 3,  'subdivision_name' => 'Mualnuam'],
            ['subdivision_id' => 8,  'district_id' => 3,  'subdivision_name' => 'Churachandpur'],
            ['subdivision_id' => 9,  'district_id' => 3,  'subdivision_name' => 'Henglep'],
            ['subdivision_id' => 10, 'district_id' => 3,  'subdivision_name' => 'Tuibong'],
            ['subdivision_id' => 11, 'district_id' => 3,  'subdivision_name' => 'Kangvai'],
            ['subdivision_id' => 12, 'district_id' => 3,  'subdivision_name' => 'Suangdoh'],
            ['subdivision_id' => 13, 'district_id' => 3,  'subdivision_name' => 'Singngat'],
            ['subdivision_id' => 14, 'district_id' => 3,  'subdivision_name' => 'Sangaikot'],
            ['subdivision_id' => 15, 'district_id' => 3,  'subdivision_name' => 'Samulamlan'],
            ['subdivision_id' => 16, 'district_id' => 3,  'subdivision_name' => 'Saikot'],
            ['subdivision_id' => 17, 'district_id' => 4,  'subdivision_name' => 'Keirao Bitra'],
            ['subdivision_id' => 18, 'district_id' => 4,  'subdivision_name' => 'Porompat'],
            ['subdivision_id' => 19, 'district_id' => 4,  'subdivision_name' => 'Sawombung'],
            ['subdivision_id' => 20, 'district_id' => 5,  'subdivision_name' => 'Lamsang'],
            ['subdivision_id' => 21, 'district_id' => 5,  'subdivision_name' => 'Patsoi'],
            ['subdivision_id' => 22, 'district_id' => 5,  'subdivision_name' => 'Lamphelpat'],
            ['subdivision_id' => 23, 'district_id' => 5,  'subdivision_name' => 'Wangoi'],
            ['subdivision_id' => 24, 'district_id' => 6,  'subdivision_name' => 'Jiribam'],
            ['subdivision_id' => 25, 'district_id' => 6,  'subdivision_name' => 'Borobekra'],
            ['subdivision_id' => 26, 'district_id' => 7,  'subdivision_name' => 'Waikhong'],
            ['subdivision_id' => 27, 'district_id' => 7,  'subdivision_name' => 'Kakching'],
            ['subdivision_id' => 28, 'district_id' => 8,  'subdivision_name' => 'Kasom Khullen'],
            ['subdivision_id' => 29, 'district_id' => 8,  'subdivision_name' => 'Sahamphung'],
            ['subdivision_id' => 30, 'district_id' => 8,  'subdivision_name' => 'Kamjong'],
            ['subdivision_id' => 31, 'district_id' => 8,  'subdivision_name' => 'Phungyar'],
            ['subdivision_id' => 32, 'district_id' => 9,  'subdivision_name' => 'Bungte Chiru'],
            ['subdivision_id' => 33, 'district_id' => 9,  'subdivision_name' => 'Champhai'],
            ['subdivision_id' => 34, 'district_id' => 9,  'subdivision_name' => 'Island'],
            ['subdivision_id' => 35, 'district_id' => 9,  'subdivision_name' => 'Kangchup Geljang'],
            ['subdivision_id' => 36, 'district_id' => 9,  'subdivision_name' => 'Kangpokpi'],
            ['subdivision_id' => 37, 'district_id' => 9,  'subdivision_name' => 'Lhungtin'],
            ['subdivision_id' => 38, 'district_id' => 9,  'subdivision_name' => 'Saikul'],
            ['subdivision_id' => 39, 'district_id' => 9,  'subdivision_name' => 'Saitu-Gamphazol'],
            ['subdivision_id' => 40, 'district_id' => 9,  'subdivision_name' => 'T Vaichong'],
            ['subdivision_id' => 41, 'district_id' => 10, 'subdivision_name' => 'Longmai'],
            ['subdivision_id' => 42, 'district_id' => 10, 'subdivision_name' => 'Khoupum'],
            ['subdivision_id' => 43, 'district_id' => 10, 'subdivision_name' => 'Haochong'],
            ['subdivision_id' => 44, 'district_id' => 10, 'subdivision_name' => 'Nungba'],
            ['subdivision_id' => 45, 'district_id' => 11, 'subdivision_name' => 'Vangai Range'],
            ['subdivision_id' => 46, 'district_id' => 11, 'subdivision_name' => 'Thanlon'],
            ['subdivision_id' => 47, 'district_id' => 11, 'subdivision_name' => 'Tipaimukh'],
            ['subdivision_id' => 48, 'district_id' => 12, 'subdivision_name' => 'Song Song'],
            ['subdivision_id' => 49, 'district_id' => 12, 'subdivision_name' => 'Paomata'],
            ['subdivision_id' => 50, 'district_id' => 12, 'subdivision_name' => 'Tadubi'],
            ['subdivision_id' => 51, 'district_id' => 12, 'subdivision_name' => 'Purul'],
            ['subdivision_id' => 52, 'district_id' => 12, 'subdivision_name' => 'Willong'],
            ['subdivision_id' => 53, 'district_id' => 12, 'subdivision_name' => 'Chilivai Phaibung'],
            ['subdivision_id' => 54, 'district_id' => 13, 'subdivision_name' => 'Tamenglong West'],
            ['subdivision_id' => 55, 'district_id' => 13, 'subdivision_name' => 'Tamenglong'],
            ['subdivision_id' => 56, 'district_id' => 13, 'subdivision_name' => 'Tamenglong North'],
            ['subdivision_id' => 57, 'district_id' => 14, 'subdivision_name' => 'Moreh'],
            ['subdivision_id' => 58, 'district_id' => 14, 'subdivision_name' => 'Machi'],
            ['subdivision_id' => 59, 'district_id' => 14, 'subdivision_name' => 'Tengnoupal'],
            ['subdivision_id' => 60, 'district_id' => 15, 'subdivision_name' => 'Thoubal'],
            ['subdivision_id' => 61, 'district_id' => 15, 'subdivision_name' => 'Lilong'],
            ['subdivision_id' => 62, 'district_id' => 16, 'subdivision_name' => 'Chingai'],
            ['subdivision_id' => 63, 'district_id' => 16, 'subdivision_name' => 'LM'],
            ['subdivision_id' => 64, 'district_id' => 16, 'subdivision_name' => 'Jessami'],
            ['subdivision_id' => 65, 'district_id' => 16, 'subdivision_name' => 'Ukhrul'],
        ];

        DB::table('subdivisions')->upsert(
            $subdivisions,
            ['subdivision_id'], // unique constraint to check existing
            ['subdivision_name', 'district_id']  // columns to update if exists
        );

        // Reset sequence/auto increment for portability
        AutoIncrement::resetIndex('subdivisions', 'subdivision_id');
    }
}
