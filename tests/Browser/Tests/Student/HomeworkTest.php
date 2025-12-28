<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Homework;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\HomeworkPage;
use Tests\DuskTestCase;

class HomeworkTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    private Homework $homework;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $this->token = JWTHelper::encode($user);

        $this->classroom = Classroom::factory()
            ->hasAttached($user, relationship: 'students')
            ->has(
                DayClass::factory()
                    ->has(
                        ClassElement::factory()
                            ->state(fn ($s) => [ "type" => "homework" ]),
                        'elements'
                    ),
                'classes'
            )
            ->create();

        $homeworkElement = ClassElement::where('type', 'homework')->first();

        $this->homework = Homework::factory()
            ->create([
                "id" => $homeworkElement->element_id,
                "title" => $homeworkElement->name,
                "is_text" => true,
                "is_file" => false,
                "can_accept_after_close" => true
            ]);
    }

    public function testShouldSendAResponseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new HomeworkPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id,
                $this->homework->classElement->class->id,
                $this->homework->id,
                $this->homework->is_text,
                $this->homework->is_file
            );

            $page->fillAndSubmit(fake()->text());

            $message = $page->getAlertMessage();

            assert($message == "Resposta enviada com sucesso");
        });
    }
}
