<?php

namespace Tests\Browser\Tests\General;

use App\Helpers\JWTHelper;
use App\Models\ClassElement;
use App\Models\Classroom;
use App\Models\DayClass;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\General\ForunPage;
use Tests\DuskTestCase;

class ForunTest extends DuskTestCase
{
    private Classroom $classroom;

    private Collection $discussions;

    public function setUp(): void
    {
        parent::setUp();

        $this->classroom = Classroom::factory()
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
                Discussion::factory()
                    ->count(rand(1, 4)),
                'discussions'
            )
            ->create();

        $this->discussions = $this->classroom
            ->discussions()
            ->orderBy('created_at')
            ->get();
    }

    public function testShouldRedirectToDiscussionPageCorrectlyAsTeacher(): void
    {
        $this->browse(function (Browser $browser) {
            $courseName = $this->classroom->courseSubject->course->name;
            $subjectName = $this->classroom->courseSubject->name;
            $classroomId = $this->classroom->id;

            $user = User::factory()->create([ "is_admin" => false, "is_teacher" => true, "is_student" => false ]);

            $token = JWTHelper::encode($user);

            $this->classroom->teachers()->attach($user->id);

            $page = new ForunPage(
                $browser,
                $token,
                $courseName,
                $subjectName,
                $classroomId,
                false
            );

            $index = rand(0, $this->discussions->count()-1);

            $id = $this->discussions[$index]->id;

            $url = "/course/$courseName/subject/$subjectName/classroom/$classroomId/forun/$id";

            $page->click($index, $url);

            $browser->assertPathIsDecoded($url);
        });
    }

    public function testShouldRedirectToDiscussionPageCorrectlyAsStudent(): void
    {
        $this->browse(function (Browser $browser) {
            $courseName = $this->classroom->courseSubject->course->name;
            $subjectName = $this->classroom->courseSubject->name;
            $classroomId = $this->classroom->id;

            $user = User::factory()->create([ "is_admin" => false, "is_teacher" => false, "is_student" => true ]);

            $token = JWTHelper::encode($user);

            $this->classroom->students()->attach($user->id);

            $page = new ForunPage(
                $browser,
                $token,
                $courseName,
                $subjectName,
                $classroomId,
                true
            );

            $index = rand(0, $this->discussions->count()-1);

            $id = $this->discussions[$index]->id;

            $url = "/course/$courseName/subject/$subjectName/classroom/$classroomId/forun/$id";

            $page->click($index, $url);

            $browser->assertPathIsDecoded($url);
        });
    }
}
