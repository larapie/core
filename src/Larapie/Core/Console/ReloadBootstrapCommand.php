<?php

namespace Larapie\Core\Console;

use Illuminate\Console\Command;
use Larapie\Core\Services\BootstrapService;

class ReloadBootstrapCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larapie:bootstrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a fresh bootstrap cache file.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = new BootstrapService();
        $service->reload();

        $this->info('Bootstrap reloaded.');
    }
}
