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
            'duration' => 45 * 60 + 30, // 00:45:30 en segundos
            'distance' => 5.25,
            'calories' => 450,
        ]);

        Activity::create([
            'user_id' => 2,
            'type_activity' => 'Cycling',
            'date' => Carbon::now()->subDay()->toDateString(),
            'duration' => 1 * 3600 + 20 * 60 + 15, // 01:20:15 en segundos
            'distance' => 25.80,
            'calories' => 700,
        ]);

        Activity::create([
            'user_id' => 3,
            'type_activity' => 'Swimming',
            'date' => Carbon::now()->toDateString(),
            'duration' => 30 * 60, // 00:30:00 en segundos
            'distance' => 1.20,
            'calories' => 300,
        ]);

    }
}
