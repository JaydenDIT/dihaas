<?php

namespace Database\Seeders;

use App\Models\VaccancyPercentage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VaccancyPercentageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure at least one record always exists with defaults
        VaccancyPercentage::updateOrCreate(
            ['id' => 1], // assumes primary key is id
            [
                'dia_percentage' => 10, // Default 10%
                'effective_date' => date('Y-m-d'), // ISO format for DB compatibility
            ]
        );
    }
}
