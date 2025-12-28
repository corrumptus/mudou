<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\EditClassroomPage;
use Tests\DuskTestCase;

class EditClassroomTest extends DuskTestCase
{
    private string $token;

    private Collection $teachers;

    private Collection $monitors;

    private Collection $students;

    private Classroom $classroom;

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

        $this->classroom = Classroom::factory()->create();

        $this->classroom->teachers()->attach($this->teachers[rand(0, 4)]->id);
        $this->classroom->monitors()->attach($this->monitors[rand(0, 4)]->id);
        $this->classroom->students()->attach($this->students[rand(0, 4)]->id);
    }

    public function testShouldCreateACourseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new EditClassroomPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id
            );

            $newClassroom = Classroom::factory()->raw();

            $teachers = fake()->randomElements(array_keys($this->teachers->all()), rand(2, 4));

            $monitors = fake()->randomElements(array_keys($this->monitors->all()), rand(0, 4));

            $students = fake()->randomElements(array_keys($this->students->all()), rand(2, 4));

            $periods = [];
            $rand = rand(1, 3);
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

            $page->fillAndSubmit(
                $newClassroom,
                $periods,
                $teachers,
                $monitors,
                $students
            );

            $text = $page->getAlertMessage();

            assert($text == "Turma atualizada com sucesso");
        });
    }
}
