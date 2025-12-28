<?php

namespace Tests\Browser\Pages\Teacher;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class EditGroupPage extends Page
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
        return "/teacher/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId/class/$this->classId/group/$this->groupId/edit";
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

    public function fillAndSubmitWithoutThemes(array $group) {
        $this->browser->type('@title', $group["title"]);
        $this->browser->type('@maxMembers', $group["max_members"]);

        $this->browser->click('@confirm');

        sleep(2); //wait for the async function to be handled
    }

    public function fillAndSubmitWithThemes(array $group) {
        $this->browser->type('@title', $group["title"]);
        $this->browser->type('@maxMembers', $group["max_members"]);

        while (\count($this->browser->elements('.remove-theme')) > 0)
            $this->browser->click('.remove-theme');

        foreach ($group["themes"] as $value) {
            $this->browser->type('@new-theme', $value);

            $this->browser->click('@bt-new-theme');
        }

        $this->browser->click('@confirm');

        sleep(2); //wait for the async function to be handled
    }
}