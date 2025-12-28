<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\DayClass;
use App\Models\Classroom;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\ClassroomPage;
use Tests\DuskTestCase;

class ClassroomTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    private array $elements;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_student" => true ]);

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

        $this->elements = Classroom::first()
            ->classes()->orderBy('date')->get()
            ->map(fn ($c) => $c->elements->all())
            ->flatten()
            ->all();
    }

    public function testShouldRedirectToTheElementPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $randomIndex = fake()->randomKey($this->elements);
            $randomElement = $this->elements[$randomIndex];

            $url = $this->baseClassroomUrl() . "/class/{$randomElement->class->id}/{$randomElement->type}/{$randomElement->element_id}";

            $page->clickElement($randomIndex, $url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheNavElementPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $randomIndex = fake()->randomKey($this->elements);
            $randomElement = $this->elements[$randomIndex];

            $url = $this->baseClassroomUrl() . "/class/{$randomElement->class->id}/{$randomElement->type}/{$randomElement->element_id}";

            $page->clickNavElement($randomIndex, $url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheAnnouncementsPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/announcements";

            $page->clickAnnouncements($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheForunPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/forun";

            $page->clickForums($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheParticipantsPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/participants";

            $page->clickParticipants($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheAnotationsPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/anotations";

            $page->clickAnotations($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheHeaderAnnouncementsPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/announcements";

            $page->clickAnnouncements($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheHeaderForunPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/forun";

            $page->clickForums($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheHeaderAnotationsPageCorreclty(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/anotations";

            $page->clickAnotations($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    private function getPage(Browser $browser) {
        return new ClassroomPage(
            $browser,
            $this->token,
            $this->classroom->courseSubject->course->name,
            $this->classroom->courseSubject->name,
            $this->classroom->id
        );
    }

    private function baseClassroomUrl() {
        $courseName = $this->classroom->courseSubject->course->name;
        $subjectName = $this->classroom->courseSubject->name;
        $classroomId = $this->classroom->id;

        return "/course/$courseName/subject/$subjectName/classroom/$classroomId";
    }
}
