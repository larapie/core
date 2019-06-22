<?php

namespace Larapie\Core\Console;

use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Eloquent\Model;
use Larapie\Core\Contracts\Bootstrapping;

class SeedCommand extends \Illuminate\Database\Console\Seeds\SeedCommand
{
    protected $service;

    public function __construct(Resolver $resolver, Bootstrapping $service)
    {
        parent::__construct($resolver);
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirmToProceed()) {
            return;
        }

        $this->resolver->setDefaultConnection($this->getDatabase());

        Model::unguarded(function () {
            foreach ($this->service->getSeeders() as $seeder) {
                $seeder = $this->laravel->make($seeder['fqn']);
                if (!isset($seeder->enabled) || $seeder->enabled) {
                    $seeder->__invoke();
                }
            }
        });

        $this->info('Database seeding completed successfully.');
    }
}
