<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class AdminCourseSubjectsPage extends Page
{
    public function __construct(
        Browser $browser,
        string $token
    ) {
        $this->browser = $browser;

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
        return '/admin/subjects';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@name-filter')
            ->assertPresent('@add')
            ->assertPresent('@subject')
            ->assertPresent('@people')
            ->assertPresent('@courses')
            ->assertPresent('@subjects')
            ->assertPresent('@classrooms')
            ->assertPresent('@permissions');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@name-filter' => '[dusk="name-filter"]',
            '@add' => '[dusk="add"]',
            '@subject' => '[dusk="subject"]',
            '@people' => '[dusk="people"]',
            '@courses' => '[dusk="courses"]',
            '@subjects' => '[dusk="subjects"]',
            '@classrooms' => '[dusk="classrooms"]',
            '@permissions' => '[dusk="permissions"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@permissions';
    }

    public function addClick() {
        $this->browser->click('@add')
            ->waitForLocation("/subject/new");
    }
}
