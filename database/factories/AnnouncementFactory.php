<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
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
            "title" => fake()->text(),
            "description" => fake()->text(),
            "created_at" => rand(),
            "classroom_id" => Classroom::factory(),
            "user_id" => User::factory()
        ];
    }
}
