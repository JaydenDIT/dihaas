<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Library\Database\AutoIncrement;
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

        $relationships = [
            ['relationship_id' => 1, 'relationship_name' => 'Wife'],
            ['relationship_id' => 2, 'relationship_name' => 'Husband'],
            ['relationship_id' => 3, 'relationship_name' => 'Son'],
            ['relationship_id' => 4, 'relationship_name' => 'Daughter'],
            ['relationship_id' => 5, 'relationship_name' => 'Unmarried Sister'],
            ['relationship_id' => 6, 'relationship_name' => 'Unmarried Brother'],
            ['relationship_id' => 7, 'relationship_name' => 'Father'],
            ['relationship_id' => 8, 'relationship_name' => 'Mother'],
            ['relationship_id' => 9, 'relationship_name' => 'Spouse'],
            ['relationship_id' => 10, 'relationship_name' => 'Other'],
        ];
        Relationship::upsert(
            $relationships,
            ['relationship_id'],
            ['relationship_name'],
        );

        // Reset sequence/auto increment for portability
        AutoIncrement::resetIndex('relationships', 'relationship_id');
    }
}
