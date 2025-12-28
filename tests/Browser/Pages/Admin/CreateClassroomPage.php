<?php

namespace Tests\Browser\Pages\Admin;

use App\Models\CourseSubject;
use Carbon\Carbon;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class CreateClassroomPage extends Page
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
        return '/classroom/new';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser
            ->assertPathIs($this->url())
            ->assertPresent('@course')
            ->assertNotPresent('@courseSubject')
            ->assertPresent('@beginDate')
            ->assertPresent('@closeDate')
            ->assertPresent('@dayOfTheWeek')
            ->assertPresent('@beginTime')
            ->assertPresent('@closeTime')
            ->assertPresent('@newPeriod')
            ->assertPresent('@teacher')
            ->assertPresent('@monitor')
            ->assertPresent('@student')
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
            '@course' => '[dusk="course"]',
            '@courseSubject' => '[dusk="courseSubject"]',
            '@beginDate' => '[dusk="beginDate"]',
            '@closeDate' => '[dusk="closeDate"]',
            '@dayOfTheWeek' => '[dusk="dayOfTheWeek"]',
            '@beginTime' => '[dusk="beginTime"]',
            '@closeTime' => '[dusk="closeTime"]',
            '@newPeriod' => '[dusk="newPeriod"]',
            '@teacher' => '[dusk="teacher"]',
            '@monitor' => '[dusk="monitor"]',
            '@student' => '[dusk="student"]',
            '@confirm' => '[dusk="confirm"]',
            '@cancel' => '[dusk="cancel"]',
        ];
    }

    public function getLastRenderElement(): string
    {
        return '@cancel';
    }

    public function fillAndSubmit(
        array $classroom,
        CourseSubject $courseSubject,
        array $periods,
        array $teachers,
        array $monitors,
        array $students
    ) {
        $this->browser->select('@course', $courseSubject->course->id);
        sleep(1); //wait for the change handler to act
        $this->browser->select('@courseSubject', $courseSubject->id);

        $this->browser->type('@beginDate', Carbon::parse($classroom["begin_date"])->format('m-d-Y'));
        $this->browser->type('@closeDate', Carbon::parse($classroom["close_date"])->format('m-d-Y'));

        foreach ($periods as $period) {
            $this->browser->select('@dayOfTheWeek', $period["dayOfTheWeek"]);

            $this->browser->type('@beginTime', Carbon::parse($period["beginTime"])->format('h'));
            $this->browser->type('@beginTime', Carbon::parse($period["beginTime"])->format('i'));
            $this->browser->type('@beginTime', Carbon::parse($period["beginTime"])->format('A'));

            $this->browser->type('@closeTime', Carbon::parse($period["closeTime"])->format('h'));
            $this->browser->type('@closeTime', Carbon::parse($period["closeTime"])->format('i'));
            $this->browser->type('@closeTime', Carbon::parse($period["closeTime"])->format('A'));

            $this->browser->click('@newPeriod');

            sleep(1); //wait for the click handler to act
        }

        foreach ($teachers as $teacher)
            $this->browser->elements('@teacher')[$teacher]->click();

        foreach ($monitors as $monitor)
            $this->browser->elements('@monitor')[$monitor]->click();

        foreach ($students as $student)
            $this->browser->elements('@student')[$student]->click();

        $this->browser->click('@confirm');

        sleep(2); //wait the async function to be handled
    }
}
