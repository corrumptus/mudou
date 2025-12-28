<?php

namespace Tests\Browser\Tests\Admin;

use App\Http\Middleware\Web\WebAuthenticationService;
use App\Models\User;
use DB;
use Laravel\Dusk\Browser;
use Str;
use Tests\Browser\Pages\Admin\ConfirmPage;
use Tests\Browser\Pages\General\LoginPage;
use Tests\DuskTestCase;

class ConfirmTest extends DuskTestCase
{
    private User $user;

    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            "is_admin" => false,
            "is_teacher" => false,
            "is_student" => true,
            "password" => null
        ]);

        $this->token = Str::random(64);

        DB::table('first_access_tokens')->insert([
            "user_id" => $this->user->id,
            "token" => $this->token
        ]);
    }

    public function testShouldLoginTheConfirmedUserCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new ConfirmPage($browser, $this->token);

            $password = fake()->password(8);

            $page->fillAndSubmit($password);

            $browser->assertPathIs("/course-subject");

            $browser->deleteCookie(WebAuthenticationService::$COOKIE_NAME);
            $browser->deleteCookie("mudou_session");

            $page = new LoginPage($browser, true);

            $page->fillAndSubmit($this->user->email, $password);

            $browser->assertPathIs("/course-subject");
        });
    }
}
