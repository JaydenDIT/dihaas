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
        $firstObject = VaccancyPercentage::first();
        if (empty($firstObject)) {
            VaccancyPercentage::create([
                'dia_percentage' => 10, //By default 10 %
                'effective_date' => date('m-d-Y'), //format: month-day-year
            ]);
        }
    }
}
