<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudioClass;
use App\Models\Schedule;
use App\Models\Membership;
use Carbon\Carbon;

class ClassMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create studio classes
        $poledance = StudioClass::create([
            'name' => 'Pole Dance',
            'slug' => 'pole-dance',
            'description' => 'Pole dance adalah olahraga yang memadukan seni tari, kekuatan, dan kelenturan dengan menggunakan tiang sebagai media latihan.',
            'image' => 'poledance2.jpg',
        ]);

        $yoga = StudioClass::create([
            'name' => 'Yoga',
            'slug' => 'yoga',
            'description' => 'Yoga adalah latihan tubuh dan pikiran yang menggabungkan gerakan (asana), pernapasan (pranayama), dan meditasi.',
            'image' => 'yoga2.jpg',
        ]);

        $pilates = StudioClass::create([
            'name' => 'Pilates',
            'slug' => 'pilates',
            'description' => 'Pilates adalah metode latihan yang berfokus pada kekuatan inti tubuh (core), postur, pernapasan, serta kontrol gerakan.',
            'image' => 'pilates2.jpg',
        ]);

        // Create schedules for Pole Dance
        Schedule::create([
            'studio_class_id' => $poledance->id,
            'instructor' => 'Carmenita',
            'start_time' => Carbon::now()->addDays(1)->setTime(12, 0),
            'end_time' => Carbon::now()->addDays(1)->setTime(14, 0),
            'price' => 250000,
        ]);

        Schedule::create([
            'studio_class_id' => $poledance->id,
            'instructor' => 'Karina',
            'start_time' => Carbon::now()->addDays(2)->setTime(14, 0),
            'end_time' => Carbon::now()->addDays(2)->setTime(16, 0),
            'price' => 250000,
        ]);

        Schedule::create([
            'studio_class_id' => $poledance->id,
            'instructor' => 'Stella',
            'start_time' => Carbon::now()->addDays(3)->setTime(15, 0),
            'end_time' => Carbon::now()->addDays(3)->setTime(17, 0),
            'price' => 250000,
        ]);

        // Create schedules for Yoga
        Schedule::create([
            'studio_class_id' => $yoga->id,
            'instructor' => 'Jennie',
            'start_time' => Carbon::now()->addDays(1)->setTime(10, 0),
            'end_time' => Carbon::now()->addDays(1)->setTime(11, 30),
            'price' => 200000,
        ]);

        Schedule::create([
            'studio_class_id' => $yoga->id,
            'instructor' => 'Daniella',
            'start_time' => Carbon::now()->addDays(2)->setTime(17, 0),
            'end_time' => Carbon::now()->addDays(2)->setTime(18, 30),
            'price' => 200000,
        ]);

        Schedule::create([
            'studio_class_id' => $yoga->id,
            'instructor' => 'Sophia',
            'start_time' => Carbon::now()->addDays(4)->setTime(18, 0),
            'end_time' => Carbon::now()->addDays(4)->setTime(19, 30),
            'price' => 200000,
        ]);

        // Create schedules for Pilates
        Schedule::create([
            'studio_class_id' => $pilates->id,
            'instructor' => 'Hany',
            'start_time' => Carbon::now()->addDays(1)->setTime(9, 30),
            'end_time' => Carbon::now()->addDays(1)->setTime(11, 0),
            'price' => 225000,
        ]);

        Schedule::create([
            'studio_class_id' => $pilates->id,
            'instructor' => 'Sabrina',
            'start_time' => Carbon::now()->addDays(3)->setTime(16, 0),
            'end_time' => Carbon::now()->addDays(3)->setTime(17, 30),
            'price' => 225000,
        ]);

        Schedule::create([
            'studio_class_id' => $pilates->id,
            'instructor' => 'Bella',
            'start_time' => Carbon::now()->addDays(5)->setTime(14, 0),
            'end_time' => Carbon::now()->addDays(5)->setTime(15, 30),
            'price' => 225000,
        ]);

        // Create memberships
        $trial = Membership::create([
            'name' => 'Trial 2 Minggu',
            'price' => 150000,
            'duration_days' => 14,
            'description' => '3x Pertemuan',
        ]);

        $monthly = Membership::create([
            'name' => 'Bulanan',
            'price' => 500000,
            'duration_days' => 30,
            'description' => '6-7x Pertemuan / bulan',
        ]);

        $premium = Membership::create([
            'name' => 'Private / Premium',
            'price' => 1000000,
            'duration_days' => 30,
            'description' => 'Kelas private dengan coach',
        ]);

        // Attach all classes to memberships
        $trial->studioClasses()->attach([$poledance->id, $yoga->id, $pilates->id]);
        $monthly->studioClasses()->attach([$poledance->id, $yoga->id, $pilates->id]);
        $premium->studioClasses()->attach([$poledance->id, $yoga->id, $pilates->id]);
    }
}
