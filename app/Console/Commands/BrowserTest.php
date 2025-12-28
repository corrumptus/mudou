<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BrowserTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:browser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run all the browser tests(laravel dusk). docker should be up to use this command';

    private int $curTime;

    private function setCurTime() {
        $this->curTime = time();
    }

    private function diferenceBetweenTimesIsGratterThan(int $n) {
        return time() - $this->curTime < 6;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pNPM = new Process(["npm", "run", "build"]);
        $pDocker = new Process(["docker", "compose", "up"]);
        $pLaravel = new Process(["php", "artisan", "serve"]);
        $pDusk = new Process(["php", "artisan", "dusk"]);

        $this->info("BUILDING THE FRONT-END");

        $pNPM->setTimeout(null);

        $pNPM->run(function ($type, $buffer) {
            // $this->info("<fg=#a3e635>[npm]</> $buffer");
        });

        $this->info("STARTING DOCKER");

        $this->setCurTime();

        $pDocker->setTimeout(null);

        $pDocker->start(function ($type, $buffer) {
            // $this->info("<fg=#38bdf8>[docker]</> $buffer");
            $this->setCurTime();
        });

        while ($pDocker->isRunning() && $this->diferenceBetweenTimesIsGratterThan(1)) {
            sleep(1);
        }

        $this->info("STARTING PHP");

        $this->setCurTime();

        $pLaravel->setTimeout(null);

        $pLaravel->start(function ($type, $buffer) {
            // $this->info("<fg=#ff0000>[laravel]</> $buffer");
            $this->setCurTime();
        });

        while ($pLaravel->isRunning() && $this->diferenceBetweenTimesIsGratterThan(1)) {
            sleep(1);
        }

        $this->info("STARTING DUSK");

        $pDusk->setTimeout(null);

        $pDusk->run(function ($type, $buffer) {
            $this->info("<fg=#9333ea>[dusk]</> $buffer");
        });

        if ($pDocker->isRunning()) $pDocker->signal(SIGTERM);
        if ($pLaravel->isRunning()) $pLaravel->signal(SIGTERM);
    }
}
