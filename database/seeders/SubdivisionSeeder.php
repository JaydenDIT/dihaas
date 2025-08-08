<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubdivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::insert("insert into subdivisions (district_id,  subdivision_name) values 
                    (1, 'Nambl'),
                    (1, 'abv'),
                    (2, 'Derv'),
                    (2, 'TRyul'),
                    (3, 'BBq'),
                    (3, 'Rts'),
                    (4, 'df'),
                    (4, 'EGV')
                ");
    }
}
