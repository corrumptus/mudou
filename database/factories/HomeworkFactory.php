<?php

namespace Database\Factories;

use App\Models\ClassElement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Homework>
 */
class HomeworkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rands = [fake()->boolean(), fake()->boolean()];

        if (!$rands[0] && !$rands[1])
            $rands[rand(0, 1)] = true;

        $isFile = $rands[0];
        $isText = $rands[1];

        $closeDateTime = fake()->dateTime();

        return [
            "id" => rand(),
            "title" => fake()->name(),
            "description" => fake()->text(),
            "begin_date_time" => fake()->dateTime($closeDateTime),
            "close_date_time" => $closeDateTime,
            "can_accept_after_close" => fake()->boolean(),
            "is_text" => $isText,
            "max_amount_caracteres" => $isText ? rand(1, getrandmax()) : 0,
            "is_file" => $isFile,
            "max_amount_files" => $isFile ? rand(1, 4) : 0,
            "file_types" => $isFile ? fake()->fileExtension() : ""
        ];
    }
}
