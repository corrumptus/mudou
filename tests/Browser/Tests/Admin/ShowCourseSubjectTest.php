<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\CourseSubject;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\ShowCourseSubjectPage;
use Tests\DuskTestCase;

class ShowCourseSubjectTest extends DuskTestCase
{
    private string $token;

    private CourseSubject $subject;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->subject = CourseSubject::factory()->Create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldRenderCoursePageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new ShowCourseSubjectPage(
                $browser,
                $this->token,
                $this->subject->course->name,
                $this->subject->name
            );

            $page->clickEdit();

            $browser->assertPathIsDecoded("/course/{$this->subject->course->name}/subject/{$this->subject->name}/edit");
        });
    }
}
