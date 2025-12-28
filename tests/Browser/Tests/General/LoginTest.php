<?php

namespace Tests\Browser\Tests\General;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\General\LoginPage;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testShouldNotLoginWithoutUsersInDB(): void
    {
        $user = User::factory()->raw();

        $this->browse(function (Browser $browser) use ($user) {
            $page = new LoginPage($browser, false);

            $page->fillAndSubmit($user['email'], 'password');

            $browser->assertPathIs('/');
            assert($page->getErrorMessage() == "Email ou senha inválidos");
        });
    }

    public function testShouldNotLoginWithUsersInDB(): void
    {
        User::factory()->create();

        $user = User::factory()->raw();

        $this->browse(function (Browser $browser) use ($user) {
            $page = new LoginPage($browser, false);

            $page->fillAndSubmit($user['email'], 'password');

            $browser->assertPathIs('/');
            assert($page->getErrorMessage() == "Email ou senha inválidos");
        });
    }

    public function testShouldLogin(): void
    {
        $user = User::factory()->create([ "is_admin" => false, "is_teacher" => false, "is_student" => true ]);

        $this->browse(function (Browser $browser) use ($user) {
            $page = new LoginPage($browser, true);

            $page->fillAndSubmit($user['email'], 'password');

            $browser->assertPathIs('/course-subject');
            assert($page->getErrorMessage() == "");
        });
    }
}
