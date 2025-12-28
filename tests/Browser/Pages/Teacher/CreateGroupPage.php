<?php

namespace Tests\Browser\Pages\Teacher;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateGroupPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private int $classId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $classId
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->classId = $classId;

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
        return "/teacher/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/group/new";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@title')
            ->assertPresent('@maxMembers')
            ->assertPresent('@new-theme')
            ->assertPresent('@bt-new-theme')
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
            '@title' => '[dusk="title"]',
            '@maxMembers' => '[dusk="maxMembers"]',
            '@new-theme' => '[dusk="new-theme"]',
            '@bt-new-theme' => '[dusk="bt-new-theme"]',
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@cancel';
    }

    public function fillAndSubmit(array $group) {
        $this->browser->type('@title', $group["title"]);
        $this->browser->type('@maxMembers', $group["max_members"]);

        foreach ($group["themes"] as $value) {
            $this->browser->type('@new-theme', $value);

            $this->browser->click('@bt-new-theme');
        }

        $this->browser->click('@confirm');

        sleep(2); //wait for the async function to be handled
    }
}
