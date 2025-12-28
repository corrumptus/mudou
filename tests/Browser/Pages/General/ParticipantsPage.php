<?php

namespace Tests\Browser\Pages\General;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ParticipantsPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private bool $hasMonitors;

    public function __construct(Browser $browser, string $token, string $courseName, string $subjectName, int $classroomId, bool $hasMonitors) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->hasMonitors = $hasMonitors;

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
        return "/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/participants";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIsDecoded($this->url())
            ->assertPresent('@teacher')
            ->assertPresent('@student')
            ->assertPresent('@chat');

        if ($this->hasMonitors)
            $browser->assertPresent('@monitor');
        else
            $browser->assertNotPresent('@monitor');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@teacher' => '[dusk="teacher"]',
            '@monitor' => '[dusk="monitor"]',
            '@student' => '[dusk="student"]',
            '@chat' => '[dusk="chat"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@chat';
    }
}
