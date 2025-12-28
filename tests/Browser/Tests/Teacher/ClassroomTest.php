<?php

namespace Tests\Browser\Tests\Teacher;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\DayClass;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Teacher\ClassroomPage;
use Tests\DuskTestCase;

class ClassroomTest extends DuskTestCase
{
    private string $token;

    private Classroom $classroom;

    private Collection $classes;

    private array $elements;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_teacher" => true ]);

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
            ->create();

        $this->classes = Classroom::first()->classes()->orderBy('date')->get();

        $this->elements = $this->classes
            ->map(fn ($c) => $c->elements->all())
            ->flatten()
            ->all();
    }

    // public function testShouldRedirectToTheElementPageCorrectly(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $page = $this->getPage($browser);

    //         $randomIndex = fake()->randomKey($this->elements);
    //         $randomElement = $this->elements[$randomIndex];

    //         $url = $this->baseClassroomUrl() . "/{$randomElement->type}/{$randomElement->element_id}";

    //         $page->clickElement($randomIndex, $url);

    //         $browser->assertPathIsDecoded($url);
    //     });
    // }

    // public function testShouldRedirectToTheNavElementPageCorrectly(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $page = $this->getPage($browser);

    //         $randomIndex = fake()->randomKey($this->elements);
    //         $randomElement = $this->elements[$randomIndex];

    //         $url = $this->baseClassroomUrl() . "/{$randomElement->type}/{$randomElement->element_id}";

    //         $page->clickNavElement($randomIndex, $url);

    //         $browser->assertPathIsDecoded($url);
    //     });
    // }

    public function testShouldRedirectToTheAnnouncementsPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/announcements";

            $page->clickAnnouncements($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    // public function testShouldRedirectToTheForumsPageCorrectly(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $page = $this->getPage($browser);

    //         $url = $this->baseClassroomUrl() . "/forun";

    //         $page->clickForums($url);

    //         $browser->assertPathIsDecoded($url);
    //     });
    // }

    public function testShouldRedirectToTheParticipantsPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/participants";

            $page->clickParticipants($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToTheHeaderAnnouncementsPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $url = $this->baseClassroomUrl() . "/announcements";

            $page->clickAnnouncements($url);

            $browser->assertPathIsDecoded($url);
        });
    }

    // public function testShouldRedirectToTheHeaderForumsPageCorrectly(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $page = $this->getPage($browser);

    //         $url = $this->baseClassroomUrl() . "/forun";

    //         $page->clickForums($url);

    //         $browser->assertPathIsDecoded($url);
    //     });
    // }

    public function testShouldRedirectToNewHomeworkPageCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $classId = $this->classes[0]->id;

            $url = $this->baseTeacherClassroomUrl() . "/class/$classId/homework/new";

            $page->clickNewHomework(1, $url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToNewGroupPageCorrectly() {
        $this->browse(function (Browser $browser) {
            $page = $this->getPage($browser);

            $classId = $this->classes[0]->id;

            $url = $this->baseTeacherClassroomUrl() . "/class/$classId/group/new";

            $page->clickNewGroup(1, $url);

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
        $course = $this->classroom->courseSubject->course->name;
        $courseSubject = $this->classroom->courseSubject->name;
        $classroomId = $this->classroom->id;

        return "/course/$course/subject/$courseSubject/classroom/$classroomId";
    }

    private function baseTeacherClassroomUrl() {
        $course = $this->classroom->courseSubject->course->name;
        $courseSubject = $this->classroom->courseSubject->name;
        $classroomId = $this->classroom->id;

        return "/teacher/course/$course/subject/$courseSubject/classroom/$classroomId";
    }
}