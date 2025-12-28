<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\CourseSubject;
use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $closeDate = fake()->date();

        return [
            'id' => rand(),
            'begin_date' => fake()->date(max:$closeDate),
            'close_date' => $closeDate,
            'is_active' => true,
            'course_subject_id' => CourseSubject::factory()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Classroom $classroom) {
            $rows = [];
            $rand = rand(1, 4);
            $possibleDays = [
                'segunda-feira',
                'terça-feira',
                'quarta-feira',
                'quinta-feira',
                'sexta-feira',
                'sábado',
                'domingo'
            ];

            for ($i = 0; $i < $rand; $i++) {
                $time1 = fake()->unique()->time('H:i');
                $time2 = fake()->unique()->time('H:i');
                $cmp = strcmp($time1, $time2);

                $rows[] = [
                    'classroom_id' => $classroom->id,
                    'dayOfTheWeek' => fake()->randomElement($possibleDays),
                    'beginTime' => $cmp < 0 ? $time1 : $time2,
                    'closeTime' => $cmp > 0 ? $time1 : $time2
                ];
            }

            DB::table('classroom_periods')->insert($rows);
        });
    }
}
