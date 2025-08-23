<?php

namespace Database\Seeders;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::create([
            'user_id' => 1,
            'type_activity' => 'Running',
            'date' => Carbon::now()->subDays(2)->toDateString(),
            'time' => '00:45:30',
            'distance' => 5.25,
            'calories' => 450,
        ]);

        Activity::create([
            'user_id' => 2,
            'type_activity' => 'Cycling',
            'date' => Carbon::now()->subDay()->toDateString(),
            'time' => '01:20:15',
            'distance' => 25.80,
            'calories' => 700,
        ]);

        Activity::create([
            'user_id' => 3,
            'type_activity' => 'Swimming',
            'date' => Carbon::now()->toDateString(),
            'time' => '00:30:00',
            'distance' => 1.20,
            'calories' => 300,
        ]);

    }
}
