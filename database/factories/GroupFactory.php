<?php

namespace Database\Factories;

use App\Models\ClassElement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => rand(),
            "title" => fake()->name(),
            "max_members" => rand(2, 4)
        ];
    }
}
