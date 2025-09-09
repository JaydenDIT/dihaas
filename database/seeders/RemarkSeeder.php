<?php

namespace Database\Seeders;

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
            ['remark' => 'Put up for approval of UO Form Fillup', 'is_active' => true],
            ['remark' => 'Verify and put up for approval of UO Form', 'is_active' => true],
            ['remark' => 'Can be approved', 'is_active' => true],
            ['remark' => 'Others', 'is_active' => true],
        ];

        DB::table('remarks')->upsert(
            $remarks,
            ['remark'], // unique constraint to check existing
            ['remark']  // columns to update if exists
        );
    }
}
