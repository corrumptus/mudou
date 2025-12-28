<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\Announcement;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\AnnouncementPage;
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

        $saws = fake()->shuffleArray([ true, true, true, false ]);

        for ($i = 0; $i < 4; $i++)
            Announcement::factory()
                ->for($this->classroom)
                ->hasAttached($user, [ "saw" => $saws[fake()->unique()->randomKey($saws)] ], 'viewers')
                ->create();

        $this->announcements = $this->classroom->announcements->all();
    }

    public function testShouldTurnIntoSawNotSawAnnouncements(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new AnnouncementPage(
                $browser,
                $this->token,
                $this->classroom->courseSubject->course->name,
                $this->classroom->courseSubject->name,
                $this->classroom->id
            );

            $firstAnnouncemenNotSaw = Announcement::whereHas('viewers', fn ($q) =>
                $q->wherePivot('saw', false)
            )->first();

            $index = array_search(
                $firstAnnouncemenNotSaw,
                $this->announcements,
                true
            );

            $page->click($index);
        });
    }
}
