<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\AdminPeoplePage;
use Tests\DuskTestCase;

class AdminPeopleTest extends DuskTestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        User::factory()->count(5)->create();
    }

    public function testShouldRenderCreateUserPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new AdminPeoplePage($browser, $this->token);

            $page->addClick();

            $browser->assertPathIs("/user/new");
        });
    }
}
