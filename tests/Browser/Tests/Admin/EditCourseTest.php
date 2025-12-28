<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\EditCoursePage;
use Tests\DuskTestCase;

class EditCourseTest extends DuskTestCase
{
    private string $token;

    private Course $editCourse;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->editCourse = Course::factory()->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldEditCourseCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new EditCoursePage($browser, $this->token, $this->editCourse->name);

            $courseAttributes = Course::factory()->raw();

            $page->fillAndSubmit($courseAttributes);

            $text = $page->getAlertMessage();

            assert($text == "Curso atualizado com sucesso");
        });
    }
}
