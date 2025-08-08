<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        DB::insert("insert into castes (caste_name) values  
                    ('OBCM'), 
                    ('OBC-NCL'), 
                    ('SC'), 
                    ('ST'), 
                    ('General')
        ");
    }
}
