<?php

namespace Database\Seeders;

use App\Models\StudioClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudioClass::create([
            'name' => 'Poledance Class',
            'slug' => Str::slug('Poledance Class'),
            'description' => 'Pole dance adalah olahraga yang memadukan seni tari, kekuatan, dan kelenturan dengan menggunakan tiang sebagai media latihan...',
            'image' => 'poledance2.jpg',
        ]);

        StudioClass::create([
            'name' => 'Yoga Class',
            'slug' => Str::slug('Yoga Class'),
            'description' => 'Yoga adalah latihan tubuh dan pikiran yang menggabungkan gerakan (asana), pernapasan (pranayama), dan meditasi...',
            'image' => 'yoga2.jpg',
        ]);

        StudioClass::create([
            'name' => 'Pilates Class',
            'slug' => Str::slug('Pilates Class'),
            'description' => 'Pilates adalah metode latihan yang berfokus pada kekuatan inti tubuh (core), postur, pernapasan, serta kontrol gerakan...',
            'image' => 'pilates2.jpg',
        ]);
    }
}
