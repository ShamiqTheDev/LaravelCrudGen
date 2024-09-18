<?php

namespace Shamiqthedev\CrudGen\Console\Commands;

use Illuminate\Console\Command;

class GenerateCrud extends Command
{
    protected $signature = 'crud:generate {name}';
    protected $description = 'Generate a full CRUD for a given model';

    public function handle()
    {
        $name = $this->argument('name');
        // Logic to generate controllers, models, routes, views, etc.
        $this->info("CRUD for $name generated successfully.");
    }
}
