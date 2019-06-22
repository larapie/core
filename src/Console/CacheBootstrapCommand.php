<?php

namespace Larapie\Core\Console;

use Illuminate\Console\Command;
use Larapie\Core\Cache\BootstrapCache;
use Larapie\Core\Services\BootstrapService;

class CacheBootstrapCommand extends Command
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
        $service->cache();

        if (BootstrapCache::cacheIsWriteable())
            $this->info('Bootstrap reloaded.');
        else
            $this->error('Bootstrap cache folder is not writeable. Make sure /bootstrap/cache is writeable. You can continue but performance will be degraded in production environments');
    }
}
