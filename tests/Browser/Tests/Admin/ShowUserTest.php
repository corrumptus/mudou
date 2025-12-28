<?php

namespace Tests\Browser\Tests\Admin;

use App\Helpers\JWTHelper;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Admin\ShowUserPage;
use Tests\DuskTestCase;

class ShowUserTest extends DuskTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([ "is_admin" => true ]);
    }

    /**
     * A Dusk test example.
     */
    public function testShouldRenderUserPageCorrectly(): void
    {
        $this->browse(function (Browser $browser) {
            $page = new ShowUserPage(
                $browser,
                JWTHelper::encode($this->user),
                $this->user->email
            );

            $page->clickEdit();

            $browser->assertPathIs("/user/{$this->user->email}/edit");
        });
    }
}
