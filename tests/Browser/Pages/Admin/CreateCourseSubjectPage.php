<?php

namespace Tests\Browser\Pages\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateCourseSubjectPage extends Page
{
    public function __construct(Browser $browser, string $token) {
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
        return '/subject/new';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@name')
            ->assertPresent('@code')
            ->assertPresent('@course')
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
            '@name' => '[dusk="name"]',
            '@code' => '[dusk="code"]',
            '@course' => '[dusk="course"]',
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]',
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@cancel';
    }

    public function fillAndSubmit(array $courseSubject) {
        $this->browser->type('@name', $courseSubject['name']);
        $this->browser->type('@code', $courseSubject['code']);
        $this->browser->select('@course', $courseSubject["course_id"]);

        $this->browser->click('@confirm');

        sleep(2); //wait the async function to be handled
    }
}
