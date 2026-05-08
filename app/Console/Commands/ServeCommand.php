<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as FrameworkServeCommand;
use Symfony\Component\Process\Process;

class ServeCommand extends FrameworkServeCommand
{
    /**
     * Start the PHP development server without stripping Windows environment
     * variables. Laravel's default reload mode removes most host variables,
     * which can make the PHP server fail to bind sockets on some Windows PHP
     * installations.
     */
    protected function startProcess($hasEnvironment)
    {
        $environment = [];

        if (is_int($this->phpServerWorkers)) {
            $environment['PHP_CLI_SERVER_WORKERS'] = $this->phpServerWorkers;
        }

        $process = new Process($this->serverCommand(), public_path(), $environment);

        $this->trap(fn () => [SIGTERM, SIGINT, SIGHUP, SIGUSR1, SIGUSR2, SIGQUIT], function ($signal) use ($process) {
            if ($process->isRunning()) {
                $process->stop(10, $signal);
            }

            exit;
        });

        $process->start($this->handleProcessOutput());

        return $process;
    }
}
