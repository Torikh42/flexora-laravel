<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Membership::create([
            'name' => 'Trial 2 Minggu',
            'price' => 150000,
            'duration_days' => 14,
            'description' => '',
        ]);

        Membership::create([
            'name' => 'Bulanan',
            'price' => 500000,
            'duration_days' => 30,
            'description' => '',
        ]);

        Membership::create([
            'name' => 'Tahunan',
            'price' => 6000000,
            'duration_days' => 365,
            'description' => '',
        ]);
    }
}
