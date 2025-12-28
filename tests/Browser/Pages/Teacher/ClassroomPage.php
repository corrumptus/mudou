<?php

namespace Tests\Browser\Pages\Teacher;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class ClassroomPage extends Page
{
    private string $courseName;

    private string $subjectName;

    private int $classroomId;

    public function __construct(
        Browser $browser,
        string $token,
        string $courseName,
        string $subjectName,
        int $classroomId
    ) {
        $this->courseName = $courseName;
        $this->subjectName = $subjectName;
        $this->classroomId = $classroomId;
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
        return "/teacher/course/$this->courseName/subject/$this->subjectName/classroom/$this->classroomId";
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIsDecoded($this->url())
            ->assertPresent('@nav-class-element')
            ->assertPresent('@class-element')
            ->assertPresent('@header-anotations')
            ->assertPresent('@header-forums')
            ->assertPresent('@header-announcements');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@nav-class-element' => '[dusk="nav-class-element"]',
            '@announcements' => '[dusk="announcements"]',
            '@forums' => '[dusk="forums"]',
            '@participants' => '[dusk="participants"]',
            '@anotations' => '[dusk="anotations"]',
            '@header-announcements' => '[dusk="header-announcements"]',
            '@header-forums' => '[dusk="header-forums"]',
            '@header-anotations' => '[dusk="header-anotations"]',
            '@class' => '[dusk="class"]',
            '@class-element' => '[dusk="class-element"]',
            '@to-new-class-element' => '[dusk="to-new-class-element"]',
            '@menu' => '[dusk="menu"]',
            '@new-homework' => '[dusk="new-homework"]',
            '@new-group' => '[dusk="new-group"]'
        ];
    }

    public function getLastRenderElement(): string {
        return '@class-element';
    }

    public function clickNavElement(int $index, string $waitUrl) {
        $element = $this->browser
            ->elements('@nav-class-element')[$index];

        $element
            ->findElement(WebDriverBy::xpath('../../button'))
            ->click();

        sleep(1);

        $element->click();

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickElement(int $index, string $waitUrl) {
        $this->browser
            ->elements('@class-element')[$index]
            ->click();

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickHeaderAnnouncements(string $waitUrl) {
        $this->browser
            ->click('@header-announcements');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickHeaderForums(string $waitUrl) {
        $this->browser
            ->click('@header-forums');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickHeaderAnotations(string $waitUrl) {
        $this->browser
            ->click('@header-anotations');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickAnnouncements(string $waitUrl) {
        $this->browser
            ->click('@announcements');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickForums(string $waitUrl) {
        $this->browser
            ->click('@forums');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickParticipants(string $waitUrl) {
        $this->browser
            ->click('@participants');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickAnotations(string $waitUrl) {
        $this->browser
            ->click('@anotations');

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickNewHomework(int $classPosition, string $waitUrl) {
        $this->browser->mouseover("[dusk=\"class\"]:nth-child($classPosition)");

        sleep(1);

        $this->browser->script([
            "document.querySelector('[dusk=\"to-new-class-element\"]').click()",
        ]);

        $this->browser->click('@new-homework');

        sleep(2);

        $this->browser->waitForLocationDecoded($waitUrl);
    }

    public function clickNewGroup(int $classIndex, string $waitUrl) {
        $this->browser->mouseover("[dusk=\"class\"]:nth-child($classIndex)");

        sleep(1);

        $this->browser->script([
            "document.querySelector('[dusk=\"to-new-class-element\"]').click()",
        ]);

        $this->browser->click('@new-group');

        sleep(2);

        $this->browser->waitForLocationDecoded($waitUrl);
    }
}