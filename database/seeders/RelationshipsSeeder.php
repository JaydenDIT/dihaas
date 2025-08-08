<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::insert("insert into relationships (relationship_name) values  
                ('Husband'),
                ('Father'),
                ('Mother'),
                ('Grandparents'),
                ('Unmarried Daughter'),
                ('Others')
                ");
    }
}
