<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Discussion;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\NewDiscussionPage;
use Tests\DuskTestCase;

class NewDiscussionTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

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
                            ->count(rand(1, 4)),
                        'elements'
                    )
                    ->count(4),
                'classes'
            )
            ->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldRenderForunPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new NewDiscussionPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id
            );

            $newDiscussion = Discussion::factory()->raw();

            $page->fillAndSubmit($newDiscussion);

            $alertMessage = $page->getAlertMessage();

            assert($alertMessage == "Discussão criada com sucesso");
        });
    }
}
