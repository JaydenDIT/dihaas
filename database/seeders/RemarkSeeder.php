<?php

namespace Database\Seeders;

use App\Library\Database\AutoIncrement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $remarks = [
            ['id' => 1, 'remark' => 'Put up for approval of UO Form Fillup', 'is_active' => true],
            ['id' => 2, 'remark' => 'Verify and put up for approval of UO Form', 'is_active' => true],
            ['id' => 3, 'remark' => 'Can be approved', 'is_active' => true],
            ['id' => 4, 'remark' => 'Others', 'is_active' => true],
        ];

        DB::table('remarks')->upsert(
            $remarks,
            ['id'], // unique constraint to check existing
            ['remark']  // columns to update if exists
        );

        // Reset sequence/auto increment for portability
        AutoIncrement::resetIndex('remarks', 'id');
    }
}
