<?php

namespace Tests\Browser\Pages\Teacher;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateGroupGroupPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    private int $classId;

    private int $groupId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId,
        int $classId,
        int $groupId
    ) {
        $this->browser = $browser;
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
        $this->classId = $classId;
        $this->groupId = $groupId;

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
        return "/teacher/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/group/$this->groupId/new";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@name')
            ->assertPresent('@member')
            ->assertPresent('@create')
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
            '@member' => '[dusk="member"]',
            '@create' => '[dusk="create"]',
            '@cancel' => '[dusk="cancel"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@cancel';
    }

    public function fillAndSubmit(array $group) {
        $this->browser->type('@name', $group["name"]);

        foreach ($group["members"] as $value)
            $this->browser->click("#user-$value");

        $this->browser->click('@create');

        sleep(2); //wait for the async function to be handled
    }
}
