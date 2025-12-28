<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Role;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\CreateUserPage;
use Tests\DuskTestCase;

class CreateUserTest extends DuskTestCase
{
    private string $token;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        Role::factory()->count(5)->create();
    }

    public function testShouldCreateAUserCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new CreateUserPage($browser, $this->token);

            $newUser = User::factory()->raw([ "is_admin" => false, "is_student" => true ]);

            $page->fillAndSubmit($newUser);

            $text = $page->getAlertMessage();

            assert(str_starts_with($text, "O link de primeiro acesso é: "));
        });
    }
}
