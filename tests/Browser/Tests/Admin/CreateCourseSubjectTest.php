<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\CreateCourseSubjectPage;
use Tests\DuskTestCase;

class CreateCourseSubjectTest extends DuskTestCase
{
    private string $token;

    private Collection $courses;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->courses = Course::factory()->count(5)->create();
    }

    public function testShouldCreateACourseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CreateCourseSubjectPage($browser, $this->token);

            $newCourseSubject = CourseSubject::factory()
                ->for($this->courses[rand(0, 4)])
                ->raw();

            $page->fillAndSubmit($newCourseSubject);

            $text = $page->getAlertMessage();

            assert($text == "Disciplina criada com sucesso");
        });
    }
}
