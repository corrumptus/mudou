<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\DiscussionPage;
use Tests\DuskTestCase;

class DiscussionTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    private Discussion $discussion;

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

        $this->discussion = Discussion::factory()
            ->for($this->classroom)
            ->for($user, 'owner')
            ->has(DiscussionComment::factory()->count(rand(1, 5)), 'comments')
            ->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldRenderForunPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            new DiscussionPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id,
                $this->discussion->id
            );
        });
    }
}
