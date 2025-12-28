<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Homework;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\CreateHomeworkPage;
use Tests\DuskTestCase;

class CreateHomeworkTest extends DuskTestCase
{
    private string $token;

    private DayClass $class;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

        $this->token = JWTHelper::encode($user);

        $classroom = Classroom::factory()
            ->hasAttached($user, relationship: 'teachers')
            ->create();

        $this->class = DayClass::factory()->for($classroom)->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldCreateHomeworkCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CreateHomeworkPage(
                $browser,
                $this->token,
                $this->class->classroom->courseSubject->course->name,
                $this->class->classroom->courseSubject->name,
                $this->class->classroom->id,
                $this->class->id
            );

            $homeworkAttributes = Homework::factory()->raw();

            $page->fillAndSubmit($homeworkAttributes);

            $text = $page->getAlertMessage();

            assert($text == "Tarefa criada com sucesso");
        });
    }
}
