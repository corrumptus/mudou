<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Course;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\AdminCoursesPage;
use Tests\DuskTestCase;

class AdminCoursesTest extends DuskTestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        Course::factory()->count(5)->create();
    }

    public function testShouldRenderCreateUserPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new AdminCoursesPage($browser, $this->token);

            $page->addClick();

            $browser->assertPathIs("/course/new");
        });
    }
}
