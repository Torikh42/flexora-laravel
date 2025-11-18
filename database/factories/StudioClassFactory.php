<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StudioClass;

use Illuminate\Support\Str;

class StudioClassFactory extends Factory
{
    protected $model = StudioClass::class;

    public function definition()
    {
        $name = $this->faker->word;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence,
            'image' => $this->faker->imageUrl,
        ];
    }
}
