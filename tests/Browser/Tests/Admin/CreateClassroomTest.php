<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\CourseSubject;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\CreateClassroomPage;
use Tests\DuskTestCase;

class CreateClassroomTest extends DuskTestCase
{
    private string $token;

    private Collection $teachers;

    private Collection $monitors;

    private Collection $students;

    private CourseSubject $courseSubject;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            "is_admin" => true,
            "is_teacher" => false,
            "is_monitor" => false,
            "is_student" => false
        ]);

        $this->token = JWTHelper::encode($user);

        User::factory()->count(5)->create([
            "is_admin" => false,
            "is_teacher" => true,
            "is_monitor" => false,
            "is_student" => false
        ]);
        $this->teachers = User::where("is_teacher", true)->get();

        User::factory()->count(5)->create([
            "is_admin" => false,
            "is_teacher" => false,
            "is_monitor" => true,
            "is_student" => false
        ]);
        $this->monitors = User::where("is_monitor", true)->get();

        User::factory()->count(5)->create([
            "is_admin" => false,
            "is_teacher" => false,
            "is_monitor" => false,
            "is_student" => true
        ]);
        $this->students = User::where("is_student", true)->get();

        $courseSubjects = CourseSubject::factory()->count(5)->create();

        $this->courseSubject = fake()->randomElement($courseSubjects->all());
    }

    public function testShouldCreateACourseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CreateClassroomPage($browser, $this->token);

            $newClassroom = Classroom::factory()->raw();

            $teachers = fake()->randomElements(array_keys($this->teachers->all()), rand(1, 4));

            $monitors = fake()->randomElements(array_keys($this->monitors->all()), rand(0, 4));

            $students = fake()->randomElements(array_keys($this->students->all()), rand(1, 4));

            $periods = [];
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

                $periods[] = [
                    'dayOfTheWeek' => fake()->randomElement($possibleDays),
                    'beginTime' => $cmp < 0 ? $time1 : $time2,
                    'closeTime' => $cmp > 0 ? $time1 : $time2
                ];
            }

            sleep(30);

            $page->fillAndSubmit(
                $newClassroom,
                $this->courseSubject,
                $periods,
                $teachers,
                $monitors,
                $students
            );

            $text = $page->getAlertMessage();

            assert($text == "Turma criada com sucesso");
        });
    }
}
