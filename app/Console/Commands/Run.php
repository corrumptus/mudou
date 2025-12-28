<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beginsTheServe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $p1 = new Process(["npm", "run", "dev"]);
        $p2 = new Process(["php", "artisan", "serve"]);

        $p1->disableOutput();
        $p2->disableOutput();

        $p1->setTimeout(null);
        $p2->setTimeout(null);

        // Captura Ctrl+C / SIGTERM (Laravel 9+ tem $this->trap)
        $this->trap([SIGINT, SIGTERM], function () use ($p1, $p2) {
            $this->warn('Encerrando processos...');
            // Primeiro tenta encerrar "educadamente"
            if ($p1->isRunning()) $p1->signal(SIGTERM);
            if ($p2->isRunning()) $p2->signal(SIGTERM);

            // Aguarda um pouco e, se necessário, força
            usleep(400_000);

            if ($p1->isRunning()) $p1->stop();
            if ($p2->isRunning()) $p2->stop();

            // Sair do comando
            exit(0);
        });

        $this->info("http://localhost:8000");

        $p1->start();
        $p2->start();

        while ($p1->isRunning() && $p2->isRunning()) {
            usleep(100_000);
        }
    }
}
