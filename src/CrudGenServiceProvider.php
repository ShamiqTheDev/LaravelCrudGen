<?php

namespace ShamiqThedev\LaravelCrudGen;

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
                \ShamiqThedev\LaravelCrudGen\Console\Commands\GenerateCrud::class,
            ]);
        }
    }
}
