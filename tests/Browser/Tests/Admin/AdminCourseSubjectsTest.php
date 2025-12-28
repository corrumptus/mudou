<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\AdminCourseSubjectsPage;
use Tests\DuskTestCase;

class AdminCourseSubjectsTest extends DuskTestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        Course::factory()
            ->has(
                CourseSubject::factory()
                    ->count(rand(1, 4)),
                'subjects'
            )
            ->count(rand(1, 3))
            ->create();
    }

    public function testShouldRenderCreateUserPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new AdminCourseSubjectsPage($browser, $this->token);

            $page->addClick();

            $browser->assertPathIs("/subject/new");
        });
    }
}
