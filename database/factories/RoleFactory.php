<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
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
            "name" => fake()->unique()->name(),
            "linked" => fake()->unique()->randomElement(["isAdmin", "isTeacher", "isStudent", "isMonitor", null])
        ];
    }
}
