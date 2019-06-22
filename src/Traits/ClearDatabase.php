<?php

namespace Larapie\Core\Traits;

use Illuminate\Foundation\Testing\DatabaseMigrations;

trait ClearDatabase
{
    use DatabaseMigrations {
        DatabaseMigrations::runDatabaseMigrations as parentMethod;
    }

    public function runDatabaseMigrations()
    {
        $this->artisan('db:reset');
    }
}
