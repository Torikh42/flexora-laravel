<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedule;
use App\Models\StudioClass;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition()
    {
        return [
            'studio_class_id' => StudioClass::factory(),
            'instructor' => $this->faker->name,
            'start_time' => $this->faker->dateTime,
            'end_time' => $this->faker->dateTime,
            'price' => $this->faker->numberBetween(50000, 200000),
        ];
    }
}
