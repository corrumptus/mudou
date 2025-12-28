<?php

namespace Database\Factories;

use App\Models\Discussion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscussionComment>
 */
class DiscussionCommentFactory extends Factory
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
            "comment" => fake()->text(),
            "created_at" => rand(),
            "discussion_id" => Discussion::factory(),
            "user_id" => User::factory()
        ];
    }
}
