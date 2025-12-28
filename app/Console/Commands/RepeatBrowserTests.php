<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RepeatBrowserTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:repeat-browser {--a|amount=5 : number of repeats} {--filter= : filter the repeated tests by class name or by test name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run the browser tests the given amount of times';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $n = $this->option("amount");
        $filter = $this->option("filter");

        $duskCommand = ["php", "artisan", "dusk", "--without-tty"];

        if ($filter != null)
            $duskCommand[] = "--filter=$filter";

        $tests = new Process($duskCommand);

        $tests->setTimeout(null);

        for ($i=1; $i < $n+1; $i++) {
            $this->info("<fg=#9333ea>[dusk run=$i]</>\n");

            $tests->run(function ($type, $buffer) {
                $this->comment($buffer);
            });
        }
    }
}
