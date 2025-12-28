<?php

namespace Tests\Browser\Tests\General;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\General\NewDiscussionCommentPage;
use Tests\DuskTestCase;

class NewDiscussionCommentTest extends DuskTestCase
{
    private string $token;

    private Discussion $discussion;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $this->token = JWTHelper::encode($user);

        $this->discussion = Discussion::factory()
            ->has(
                Classroom::factory()
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
            )
            ->for($user, 'owner')
            ->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldRenderForunPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new NewDiscussionCommentPage(
                $browser,
                $this->token,
                $this->discussion->classroom->courseSubject->course->name,
                $this->discussion->classroom->courseSubject->name,
                $this->discussion->classroom->id,
                $this->discussion->id
            );

            $newComment = DiscussionComment::factory()->raw();

            $page->fillAndSubmit($newComment);

            $alertMessage = $page->getAlertMessage();

            assert($alertMessage == "Comentário criado com sucesso");
        });
    }
}
