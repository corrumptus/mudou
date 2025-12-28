<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\EditCourseSubjectPage;
use Tests\DuskTestCase;

class EditCourseSubjectTest extends DuskTestCase
{
    private string $token;

    private Course $newCourse;

    private CourseSubject $editCourseSubject;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->editCourseSubject = CourseSubject::factory()->create();

        $this->newCourse = Course::factory()->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldEditCourseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new EditCourseSubjectPage(
                $browser,
                $this->token,
                $this->editCourseSubject->course->name,
                $this->editCourseSubject->name
            );

            $courseAttributes = CourseSubject::factory()
                ->for($this->newCourse)
                ->raw();

            $page->fillAndSubmit($courseAttributes);

            $text = $page->getAlertMessage();

            assert($text == "Disciplina atualizada com sucesso");
        });
    }
}
