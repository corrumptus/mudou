<?php

namespace Tests\Browser\Pages\General;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ForunPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private bool $isStudent;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        bool $isStudent
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->isStudent = $isStudent;

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
        return "/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/forun";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@discussion');

        if ($this->isStudent)
            $browser->assertPresent('@add');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@add' => '[dusk="add"]',
            '@discussion' => '[dusk="discussion"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@discussion';
    }

    public function add() {
        $this->browser->click('@add');
    }

    public function click(int $index, string $waitUrl) {
        $this->browser->elements('@discussion')[$index]->click();

        $this->browser->waitForLocationDecoded($waitUrl);
    }
}
