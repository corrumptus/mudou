<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseSubject>
 */
class CourseSubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => rand(),
            'name' => $this->generateName(),
            'code' => fake()->unique()->currencyCode(),
            'color' => fake()->hexColor(),
            'course_id' => Course::factory(),
            'is_active' => true
        ];
    }

    private function generateName() {
        while (true) {
            $name = fake()->name();

            if (strpos($name, "'") == false)
                return $name;
        }
    }
}
