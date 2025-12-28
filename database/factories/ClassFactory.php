<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\DayClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DayClass>
 */
class ClassFactory extends Factory
{
    protected $model = DayClass::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => rand(),
            "name" => fake()->company(),
            "date" => fake()->date(),
            "classroom_id" => Classroom::factory()
        ];
    }
}
