<?php

namespace Tests\Browser\Pages\Teacher;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CourseSubjectPage extends Page
{
    private array $userTypes;

    public function __construct(Browser $browser, string $token, array $userTypes) {
        $this->browser = $browser;
        $this->userTypes = $userTypes;

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
        return '/teacher/course-subject';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@subject');

        if ($this->userTypes['isAdmin'])
            $browser->assertPresent('@admin');
        else
            $browser->assertNotPresent('@admin');

        if ($this->userTypes['isTeacher'])
            $browser->assertPresent('@teacher');
        else
            $browser->assertNotPresent('@teacher');

        if ($this->userTypes['isStudent'])
            $browser->assertPresent('@subjects');
        else
            $browser->assertNotPresent('@subjects');

        if ($this->userTypes['isMonitor'])
            $browser->assertPresent('@monitors');
        else
            $browser->assertNotPresent('@monitors');

        if ($this->userTypes['isStudent'])
            $browser->assertPresent('@anotations');
        else
            $browser->assertNotPresent('@anotations');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@admin' => '[dusk="admin"]',
            '@teacher' => '[dusk="teacher"]',
            '@subjects' => '[dusk="subjects"]',
            '@monitors' => '[dusk="monitors"]',
            '@anotations' => '[dusk="anotations"]',
            '@subject' => '[dusk=subject]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@subject';
    }

    public function click(int $index, string $waitUrl) {
        $this->browser
            ->elements('@subject')[$index]
            ->click();

        $this->browser->waitForLocationDecoded($waitUrl, 5);
    }
}
