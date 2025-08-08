<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::insert("insert into qualifications (qualification_name) values  
                ('SLSS'),
                ('HSC'),
                ('Diploma'),
                ('Others')
                ");
    }
}
