<?php

namespace Shamiqthedev\CrudGen;

use Illuminate\Support\ServiceProvider;

class CrudGenServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register services, if any
    }

    public function boot()
    {
        // Commands, migrations, or file publishes
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Shamiqthedev\CrudGen\Console\Commands\GenerateCrud::class,
            ]);
        }
    }
}
