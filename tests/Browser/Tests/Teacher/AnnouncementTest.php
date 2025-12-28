<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\Announcement;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\AnnouncementPage;
use Tests\DuskTestCase;

class AnnouncementTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    private array $announcements;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $this->token = JWTHelper::encode($user);

        $this->classroom = Classroom::factory()
            ->hasAttached($user, relationship: 'teachers')
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
            ->has(
                Announcement::factory()
                    ->count(4)
                    ->hasAttached($user, [ "saw" => fake()->boolean()], 'viewers')
            )
            ->create();

        $this->announcements = $this->classroom->announcements->all();
    }

    public function testShouldRedirectToNewAnnouncementPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new AnnouncementPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id
            );

            $page->toNew();
        });
    }
}
