<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DocumentListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = Config::get('documentCriteria');

        $documents = [
            ['document_name' => 'Compulsory Document', 'document_criteria' => 'compulsory'],
            ['document_name' => 'Caste Certificate', 'document_criteria' => 'caste'],
            ['document_name' => 'Handicapped Certificate', 'document_criteria' => 'handicapped'],
            ['document_name' => 'Class X Certificate', 'document_criteria' => 'class_x'],
        ];

        foreach ($documents as $doc) {
            // Ensure the key exists in config
            if (!array_key_exists($doc['document_criteria'], $criteria)) {
                $this->command->error("Criteria '{$doc['document_criteria']}' not found in documentCriteria config.");
                continue;
            }

            DB::table('document_list')->insert([
                'document_name' => $doc['document_name'],
                'document_criteria' => $doc['document_criteria'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // $this->command->info('Document list seeded successfully!');
    }
}
