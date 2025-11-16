<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::create([
            'studio_class_id' => 1,
            'instructor' => 'Carmenita',
            'start_time' => '2025-09-06 12:00:00',
            'end_time' => '2025-09-06 14:00:00',
            'price' => 250000,
        ]);

        Schedule::create([
            'studio_class_id' => 1,
            'instructor' => 'Karina',
            'start_time' => '2025-09-07 14:00:00',
            'end_time' => '2025-09-07 16:00:00',
            'price' => 250000,
        ]);

        Schedule::create([
            'studio_class_id' => 2,
            'instructor' => 'Jennie',
            'start_time' => '2025-09-06 12:00:00',
            'end_time' => '2025-09-06 13:00:00',
            'price' => 250000,
        ]);

        Schedule::create([
            'studio_class_id' => 3,
            'instructor' => 'Hany',
            'start_time' => '2025-09-05 09:30:00',
            'end_time' => '2025-09-05 11:30:00',
            'price' => 250000,
        ]);
    }
}
