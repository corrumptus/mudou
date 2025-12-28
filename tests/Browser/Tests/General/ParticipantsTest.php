<?php

namespace Tests\Browser\Tests\General;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\General\ParticipantsPage;
use Tests\DuskTestCase;

class ParticipantsTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    private bool $hasMonitors;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

        $this->token = JWTHelper::encode($user);

        $amountMonitors = rand(0, 2);

        $this->hasMonitors = $amountMonitors > 0;

        $this->classroom = Classroom::factory()
            ->hasAttached(
                $user,
                [],
                'teachers'
            )
            ->hasAttached(
                User::factory()->count($amountMonitors),
                [],
                'monitors'
            )
            ->hasAttached(
                User::factory()->count(rand(1, 10)),
                [],
                'students'
            )
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
    public function testShouldRenderParticipantsPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            new ParticipantsPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id,
                $this->hasMonitors
            );
        });
    }
}
