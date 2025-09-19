<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Library\Database\AutoIncrement;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\DB;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['state_id' => 1, 'state_name' => 'Manipur'],
        ];
        State::upsert(
            $states,
            ['state_id'],
            ['state_name']
        );

        // Reset sequence/auto increment for portability
        AutoIncrement::resetIndex('states', 'state_id');
    }
}
