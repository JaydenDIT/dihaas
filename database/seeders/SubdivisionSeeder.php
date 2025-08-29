<?php

namespace Database\Seeders;

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
            ['district_id' => 1, 'subdivision_name' => 'Nambol'],
            ['district_id' => 1, 'subdivision_name' => 'Moirang'],
            ['district_id' => 1, 'subdivision_name' => 'Bishnupur'],
            ['district_id' => 2, 'subdivision_name' => 'Chakpikarong'],
            ['district_id' => 2, 'subdivision_name' => 'Khengjoy'],
            ['district_id' => 2, 'subdivision_name' => 'Chandel'],
            ['district_id' => 3, 'subdivision_name' => 'Mualnuam'],
            ['district_id' => 3, 'subdivision_name' => 'Churachandpur'],
            ['district_id' => 3, 'subdivision_name' => 'Henglep'],
            ['district_id' => 3, 'subdivision_name' => 'Tuibong'],
            ['district_id' => 3, 'subdivision_name' => 'Kangvai'],
            ['district_id' => 3, 'subdivision_name' => 'Suangdoh'],
            ['district_id' => 3, 'subdivision_name' => 'Singngat'],
            ['district_id' => 3, 'subdivision_name' => 'Sangaikot'],
            ['district_id' => 3, 'subdivision_name' => 'Samulamlan'],
            ['district_id' => 3, 'subdivision_name' => 'Saikot'],
            ['district_id' => 4, 'subdivision_name' => 'Keirao Bitra'],
            ['district_id' => 4, 'subdivision_name' => 'Porompat'],
            ['district_id' => 4, 'subdivision_name' => 'Sawombung'],
            ['district_id' => 5, 'subdivision_name' => 'Lamsang'],
            ['district_id' => 5, 'subdivision_name' => 'Patsoi'],
            ['district_id' => 5, 'subdivision_name' => 'Lamphelpat'],
            ['district_id' => 5, 'subdivision_name' => 'Wangoi'],
            ['district_id' => 6, 'subdivision_name' => 'Jiribam'],
            ['district_id' => 6, 'subdivision_name' => 'Borobekra'],
            ['district_id' => 7, 'subdivision_name' => 'Waikhong'],
            ['district_id' => 7, 'subdivision_name' => 'Kakching'],
            ['district_id' => 8, 'subdivision_name' => 'Kasom Khullen'],
            ['district_id' => 8, 'subdivision_name' => 'Sahamphung'],
            ['district_id' => 8, 'subdivision_name' => 'Kamjong'],
            ['district_id' => 8, 'subdivision_name' => 'Phungyar'],
            ['district_id' => 9, 'subdivision_name' => 'Bungte Chiru'],
            ['district_id' => 9, 'subdivision_name' => 'Champhai'],
            ['district_id' => 9, 'subdivision_name' => 'Island'],
            ['district_id' => 9, 'subdivision_name' => 'Kangchup Geljang'],
            ['district_id' => 9, 'subdivision_name' => 'Kangpokpi'],
            ['district_id' => 9, 'subdivision_name' => 'Lhungtin'],
            ['district_id' => 9, 'subdivision_name' => 'Saikul'],
            ['district_id' => 9, 'subdivision_name' => 'Saitu-Gamphazol'],
            ['district_id' => 9, 'subdivision_name' => 'T Vaichong'],
            ['district_id' => 10, 'subdivision_name' => 'Longmai'],
            ['district_id' => 10, 'subdivision_name' => 'Khoupum'],
            ['district_id' => 10, 'subdivision_name' => 'Haochong'],
            ['district_id' => 10, 'subdivision_name' => 'Nungba'],
            ['district_id' => 11, 'subdivision_name' => 'Vangai Range'],
            ['district_id' => 11, 'subdivision_name' => 'Thanlon'],
            ['district_id' => 11, 'subdivision_name' => 'Tipaimukh'],
            ['district_id' => 12, 'subdivision_name' => 'Song Song'],
            ['district_id' => 12, 'subdivision_name' => 'Paomata'],
            ['district_id' => 12, 'subdivision_name' => 'Tadubi'],
            ['district_id' => 12, 'subdivision_name' => 'Purul'],
            ['district_id' => 12, 'subdivision_name' => 'Willong'],
            ['district_id' => 12, 'subdivision_name' => 'Chilivai Phaibung'],
            ['district_id' => 13, 'subdivision_name' => 'Tamenglong West'],
            ['district_id' => 13, 'subdivision_name' => 'Tamenglong'],
            ['district_id' => 13, 'subdivision_name' => 'Tamenglong North'],
            ['district_id' => 14, 'subdivision_name' => 'Moreh'],
            ['district_id' => 14, 'subdivision_name' => 'Machi'],
            ['district_id' => 14, 'subdivision_name' => 'Tengnoupal'],
            ['district_id' => 15, 'subdivision_name' => 'Thoubal'],
            ['district_id' => 15, 'subdivision_name' => 'Lilong'],
            ['district_id' => 16, 'subdivision_name' => 'Chingai'],
            ['district_id' => 16, 'subdivision_name' => 'LM'],
            ['district_id' => 16, 'subdivision_name' => 'Jessami'],
            ['district_id' => 16, 'subdivision_name' => 'Ukhrul'],
        ];

        DB::table('subdivisions')->upsert(
            $subdivisions,
            ['subdivision_name'], // unique constraint to check existing
            ['district_id']  // columns to update if exists
        );
    }
}
