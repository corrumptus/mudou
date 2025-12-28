<?php

namespace Tests\Browser\Tests\Student;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Student\CourseSubjectPage;
use Tests\DuskTestCase;

class CourseSubjectTest extends DuskTestCase
{
    private User $user;

    private string $token;

    private Collection $classrooms;

    private int $amount;

    public function setUp(): void {
        parent::setUp();

        $this->user = User::factory()->create([
            "is_admin" => false,
            "is_teacher" => false,
            "is_student" => true
        ]);
        $this->token = JWTHelper::encode($this->user);

        Classroom::factory()
            ->hasAttached($this->user, relationship: 'students')
            ->count(rand(1, 4))
            ->create();

        $this->classrooms = Classroom::get();
    }

    public function testShouldRedirectToTheClassPage(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CourseSubjectPage(
                $browser,
                $this->token,
                [
                    "isAdmin" => $this->user->is_admin,
                    "isTeacher" => $this->user->is_teacher,
                    "isStudent" => $this->user->is_student,
                    "isMonitor" => $this->user->is_monitor
                ]
            );

            $randomCard = rand(0, $this->classrooms->count()-1);

            $course = $this->classrooms[$randomCard]->courseSubject->course->name;
            $courseSubject = $this->classrooms[$randomCard]->courseSubject->name;
            $classroomId = $this->classrooms[$randomCard]->id;

            $url = "/course/$course/subject/$courseSubject/classroom/$classroomId";

            $page->click($randomCard, $url);

            $browser->assertPathIsDecoded($url);
        });
    }
}
