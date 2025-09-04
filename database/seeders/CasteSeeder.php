<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Caste;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* DB::insert("insert into castes (caste_name) values  
                    ('OBCM'), 
                    ('OBC-NCL'), 
                    ('SC'), 
                    ('ST'), 
                    ('General')
        "); */

        $castes = [
            ['caste_name' => 'OBCM'],
            ['caste_name' => 'OBC-NCL'],
            ['caste_name' => 'SC'],
            ['caste_name' => 'ST'],
            ['caste_name' => 'General'],
        ];

        Caste::upsert(
            $castes,
            ['caste_name'], // unique
            [] // nothing to update
        );
    }
}
