<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Library\Database\AutoIncrement;
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
            ['caste_id' => 1, 'caste_name' => 'OBCM'],
            ['caste_id' => 2, 'caste_name' => 'OBC-NCL'],
            ['caste_id' => 3, 'caste_name' => 'SC'],
            ['caste_id' => 4, 'caste_name' => 'ST'],
            ['caste_id' => 5, 'caste_name' => 'General'],
        ];

        Caste::upsert(
            $castes,
            ['caste_id'], // unique
            ['caste_name'], // update if exists
        );

        // reset auto increment / sequence
        AutoIncrement::resetIndex('castes', 'caste_id');
    }
}
