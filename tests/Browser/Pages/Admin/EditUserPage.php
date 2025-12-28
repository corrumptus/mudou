<?php

namespace Tests\Browser\Pages\Admin;

use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class EditUserPage extends Page
{
    private string $email;

    public function __construct(Browser $browser, string $token, string $email) {
        $this->browser = $browser;
        $this->email = $email;

        $browser
            ->loginCookie($token)
            ->visit($this->url())
            ->waitFor($this->getLastRenderElement());

        $this->assert($browser);
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return "/user/$this->email/edit";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@email')
            ->assertPresent('@name')
            ->assertPresent('@password')
            ->assertPresent('@birthDate')
            ->assertPresent('@cpf')
            ->assertPresent('@admin')
            ->assertPresent('@teacher')
            ->assertPresent('@student')
            ->assertPresent('@monitor')
            ->assertPresent('@img')
            ->assertPresent('[dusk^="role-"]')
            ->assertPresent('@confirm')
            ->assertPresent('@cancel');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@email' => '[dusk="email"]',
            '@name' => '[dusk="name"]',
            '@password' => '[dusk"password"]',
            '@birthDate' => '[dusk="birthDate"]',
            '@cpf' => '[dusk="cpf"]',
            '@admin' => '[dusk="admin"]',
            '@teacher' => '[dusk="teacher"]',
            '@student' => '[dusk="student"]',
            '@monitor' => '[dusk="monitor"]',
            '@img' => '[dusk="img"]',
            '@roles' => '[dusk^="role-"]',
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]',
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@cancel';
    }

    public function fillAndSubmit(array $user) {
        $this->browser->type('@email', $user['email']);
        $this->browser->type('@name', $user['name']);
        $this->browser->type('@password', $user["password"]);
        $this->browser->type('@birthDate', Carbon::createFromFormat('Y-m-d', $user["birth_date"])->format('m-d-Y'));
        $this->browser->type('@cpf', $user['cpf']);

        $this->browser->click('@confirm');

        sleep(2); //wait the async function to be handled
    }
}
