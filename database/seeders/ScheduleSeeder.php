<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Instructors
        $poleDanceInstructors = ['Carmenita', 'Karina', 'Sasha', 'Melissa'];
        $pilatesInstructors = ['Jennie', 'Diana', 'Victoria', 'Luna'];
        $yogaInstructors = ['Hany', 'Bella', 'Sophie', 'Rosa'];

        $polePrice = 250000;
        $pilatesPrice = 200000;
        $yogaPrice = 180000;

        // === POLEDANCE: Dates 11, 19, 25 of each month (Nov & Dec 2025) ===
        foreach ([11, 12] as $month) {
            foreach ([11, 19, 25] as $day) {
                // Morning session (09:00-11:00)
                Schedule::create([
                    'studio_class_id' => 1, // Poledance
                    'instructor' => $poleDanceInstructors[array_rand($poleDanceInstructors)],
                    'start_time' => Carbon::create(2025, $month, $day, 9, 0),
                    'end_time' => Carbon::create(2025, $month, $day, 11, 0),
                    'price' => $polePrice,
                ]);

                // Afternoon session (14:00-16:00)
                Schedule::create([
                    'studio_class_id' => 1,
                    'instructor' => $poleDanceInstructors[array_rand($poleDanceInstructors)],
                    'start_time' => Carbon::create(2025, $month, $day, 14, 0),
                    'end_time' => Carbon::create(2025, $month, $day, 16, 0),
                    'price' => $polePrice,
                ]);

                // Evening session (17:00-19:00)
                Schedule::create([
                    'studio_class_id' => 1,
                    'instructor' => $poleDanceInstructors[array_rand($poleDanceInstructors)],
                    'start_time' => Carbon::create(2025, $month, $day, 17, 0),
                    'end_time' => Carbon::create(2025, $month, $day, 19, 0),
                    'price' => $polePrice,
                ]);
            }
        }

        // === PILATES: Monday, Wednesday, Friday (Nov & Dec 2025) ===
        $startDate = Carbon::create(2025, 11, 1);
        $endDate = Carbon::create(2025, 12, 31);

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Monday (1), Wednesday (3), Friday (5)
            if (in_array($date->dayOfWeek, [1, 3, 5])) {
                // Morning session (07:00-08:30)
                Schedule::create([
                    'studio_class_id' => 3, // Pilates
                    'instructor' => $pilatesInstructors[array_rand($pilatesInstructors)],
                    'start_time' => $date->copy()->setTime(7, 0),
                    'end_time' => $date->copy()->setTime(8, 30),
                    'price' => $pilatesPrice,
                ]);

                // Afternoon session (15:00-16:30)
                Schedule::create([
                    'studio_class_id' => 3,
                    'instructor' => $pilatesInstructors[array_rand($pilatesInstructors)],
                    'start_time' => $date->copy()->setTime(15, 0),
                    'end_time' => $date->copy()->setTime(16, 30),
                    'price' => $pilatesPrice,
                ]);

                // Evening session (18:00-19:30)
                Schedule::create([
                    'studio_class_id' => 3,
                    'instructor' => $pilatesInstructors[array_rand($pilatesInstructors)],
                    'start_time' => $date->copy()->setTime(18, 0),
                    'end_time' => $date->copy()->setTime(19, 30),
                    'price' => $pilatesPrice,
                ]);
            }
        }

        // === YOGA: Tuesday, Thursday, Saturday (Nov & Dec 2025) ===
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Tuesday (2), Thursday (4), Saturday (6)
            if (in_array($date->dayOfWeek, [2, 4, 6])) {
                // Morning session (06:00-07:30)
                Schedule::create([
                    'studio_class_id' => 2, // Yoga
                    'instructor' => $yogaInstructors[array_rand($yogaInstructors)],
                    'start_time' => $date->copy()->setTime(6, 0),
                    'end_time' => $date->copy()->setTime(7, 30),
                    'price' => $yogaPrice,
                ]);

                // Evening session (17:00-18:30)
                Schedule::create([
                    'studio_class_id' => 2,
                    'instructor' => $yogaInstructors[array_rand($yogaInstructors)],
                    'start_time' => $date->copy()->setTime(17, 0),
                    'end_time' => $date->copy()->setTime(18, 30),
                    'price' => $yogaPrice,
                ]);
            }
        }
    }
}
