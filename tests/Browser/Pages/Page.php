<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    protected Browser $browser;

    /**
     * Get the global element shortcuts for the site.
     *
     * @return array<string, string>
     */
    public static function siteElements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function getLastRenderElement(): string {
        return 'div#app';
    }

    public function getAlertMessage() {
        $message = $this->browser->driver->switchTo()->alert()->getText();

        $this->browser->driver->switchTo()->alert()->accept();

        return $message;
    }
}
