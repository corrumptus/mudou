<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\Role;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\EditUserPage;
use Tests\DuskTestCase;

class EditUserTest extends DuskTestCase
{
    private string $token;

    private User $editUser;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([ "is_admin" => true ]);

        $this->token = JWTHelper::encode($user);

        $this->editUser = User::factory()->create();

        Role::factory()->count(5)->create();
    }

    /**
     * A Dusk test example.
     */
    public function testShouldEditUserCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new EditUserPage($browser, $this->token, $this->editUser->email);

            $userAttributes = User::factory()->raw();

            $page->fillAndSubmit($userAttributes);

            $text = $page->getAlertMessage();

            assert($text == "Usuário atualizado com sucesso");
        });
    }
}
