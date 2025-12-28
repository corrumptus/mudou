<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Symfony\Component\Process\Process;

class RunDuskSetUp extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:setup {--b|build : rebuild the front-end before the setup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the selenium docker container and the application server for faster testing. docker should be up. only one setup can be made at a time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shouldBuild = $this->option("build");

        if ($shouldBuild) {
            (new Process(["npm", "run", "build"]))->run();
        }

        $pDocker = new Process(["docker", "compose", "up"]);
        $pLaravel = new Process(["php", "artisan", "serve"]);

        $pDocker->disableOutput();
        $pLaravel->disableOutput();

        $pDocker->setTimeout(null);
        $pLaravel->setTimeout(null);

        // Captura Ctrl+C / SIGTERM (Laravel 9+ tem $this->trap)
        $this->trap([SIGINT, SIGTERM], function () use ($pDocker, $pLaravel) {
            $this->warn('Encerrando processos...');
            // Primeiro tenta encerrar "educadamente"
            if ($pDocker->isRunning()) $pDocker->signal(SIGTERM);
            if ($pLaravel->isRunning()) $pLaravel->signal(SIGTERM);

            // Aguarda um pouco e, se necessário, força
            sleep(5);

            if ($pDocker->isRunning()) $pDocker->stop();
            if ($pLaravel->isRunning()) $pLaravel->stop();

            // Sair do comando
            exit(0);
        });

        $pDocker->start();
        $pLaravel->start();

        while ($pDocker->isRunning() && $pLaravel->isRunning()) {
            usleep(100_000);
        }
    }
}
