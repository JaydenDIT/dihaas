<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Relationship;
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
        /* DB::insert("insert into relationships (relationship_name) values  
                ('Wife'),
                ('Husband'),
                ('Son'),
                ('Daughter'),
                ('Unmarried Sister'),
                ('Unmaried Brother')
                "); */
        $relationships = [
            ['relationship_name' => 'Wife'],
            ['relationship_name' => 'Husband'],
            ['relationship_name' => 'Son'],
            ['relationship_name' => 'Daughter'],
            ['relationship_name' => 'Unmarried Sister'],
            ['relationship_name' => 'Unmarried Brother'],
        ];
        Relationship::upsert(
            $relationships,
            ['relationship_name'],
            []
        );
    }
}
