<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::insert("insert into states (state_id,  state_name) values 
                    (1, 'Manipur'),
                    (2, 'Mizoram'),
                    (3, 'Nagaland'),
                    (4, 'Tripura'),
                    (5, 'Arunachal Pradesh'),
                    (6, 'Assam'),
                    (7, 'Meghalaya'),
                    (8, 'Sikkim'),
                    (9, 'West Bengal'),
                    (10, 'Bihar'),
                    (11, 'Jharkhand'),
                    (12, 'Odisha')
                ");
    }
}
