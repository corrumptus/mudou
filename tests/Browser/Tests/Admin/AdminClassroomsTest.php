<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Classroom;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\AdminClassroomsPage;
use Tests\DuskTestCase;

class AdminClassroomsTest extends DuskTestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        Classroom::factory()
            ->count(5)
            ->hasAttached(
                User::factory()->count(2),
                [],
                'teachers'
            )
            ->hasAttached(
                User::factory()->count(2),
                [],
                'monitors'
            )
            ->hasAttached(
                User::factory()->count(2),
                [],
                'students'
            )
            ->create();
    }

    public function testShouldRenderCreateUserPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new AdminClassroomsPage($browser, $this->token);

            $page->addClick();

            $browser->assertPathIs("/classroom/new");
        });
    }
}
