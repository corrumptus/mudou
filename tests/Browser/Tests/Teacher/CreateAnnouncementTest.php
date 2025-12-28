<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\Announcement;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\CreateAnnouncementPage;
use Tests\DuskTestCase;

class CreateAnnouncementTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->classroom = Classroom::factory()
            ->hasAttached($user, relationship: 'teachers')
            ->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldCreateAnnouncementCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CreateAnnouncementPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id
            );

            $announcementAttributes = Announcement::factory()->raw();

            $page->fillAndSubmit($announcementAttributes);

            $text = $page->getAlertMessage();

            assert($text == "Aviso criado com sucesso");
        });
    }
}
