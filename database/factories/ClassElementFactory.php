<?php

namespace Database\Factories;

use App\Models\DayClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassElement>
 */
class ClassElementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "type" => fake()->randomElement(["homework", "group"]),
            // "type" => fake()->randomElement(["file", "link", "text", "poll", "homework", "group", "quiz"]),
            "element_id" => rand(),
            "class_id" => DayClass::factory()
        ];
    }
}
