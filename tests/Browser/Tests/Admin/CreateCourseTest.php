<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\CreateCoursePage;
use Tests\DuskTestCase;

class CreateCourseTest extends DuskTestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);
    }

    public function testShouldCreateACourseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CreateCoursePage($browser, $this->token);

            $newCourse = Course::factory()->raw();

            $page->fillAndSubmit($newCourse);

            $text = $page->getAlertMessage();

            assert($text == "Curso criado com sucesso");
        });
    }
}
