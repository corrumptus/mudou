<?php

namespace Database\Factories;

use App\Models\Homework;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HomeworkTextResponse>
 */
class HomeworkTextResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "text" => fake()->text(),
            "user_id" => User::factory(),
            "homework_id" => Homework::factory()
        ];
    }
}
