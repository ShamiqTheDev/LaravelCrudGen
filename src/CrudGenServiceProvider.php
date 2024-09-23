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
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeAll::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeInterface::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeRepository::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeTrait::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeController::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeException::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakePolicy::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeRequest::class,
                \ShamiqThedev\LaravelCrudGen\Console\Commands\MakeService::class,
            ]);
        }
    }
}
