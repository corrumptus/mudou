<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\ShowCoursePage;
use Tests\DuskTestCase;

class ShowCourseTest extends DuskTestCase
{
    private string $token;

    private Course $course;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->course = Course::factory()->Create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldRenderCoursePageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new ShowCoursePage(
                $browser,
                $this->token,
                $this->course->name
            );

            $page->clickEdit();

            $browser->assertPathIsDecoded("/course/{$this->course->name}/edit");
        });
    }
}
