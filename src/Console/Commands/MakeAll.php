<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:make {name} {--model} {--migration} {--repository} {--interface} {--controller} {--requests} {--policy} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new repository, interface, model, migration, controller, requests, and policy';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelName = Str::studly($name);

        // Create directories if they don't exist
        $this->createDirectories();

        if ($this->option('all')) {
            $this->createInterface($name);
            $this->createRepository($name);
            $this->createMigration($name);
            $this->createModel($modelName);
            $this->createController($modelName);
            $this->createRequests($modelName);
            $this->createPolicy($name);
        } else {
            if ($this->option('repository')) {
                $this->createRepository($name);
            }

            if ($this->option('interface')) {
                $this->createInterface($name);
            }

            if ($this->option('model')) {
                $this->createModel($modelName);
            }

            if ($this->option('migration')) {
                $this->createMigration($name);
            }

            if ($this->option('controller')) {
                $this->createController($modelName);
            }

            if ($this->option('requests')) {
                $this->createRequests($modelName);
            }

            if ($this->option('policy')) {
                $this->createPolicy($name);
            }
        }
    }

    /**
     * Create necessary directories.
     */
    protected function createDirectories()
    {
        $directories = [
            app_path('Repositories'),
            app_path('Interfaces'),
            app_path('Http/Controllers/Api'),
            app_path('Policies'),
        ];

        foreach ($directories as $directory) {
            if (!$this->files->isDirectory($directory)) {
                $this->files->makeDirectory($directory, 0755, true);
            }
        }
    }

    /**
     * Create the interface file.
     *
     * @param string $name
     */
    protected function createInterface($name)
    {
        $interfaceName = $name . 'Interface';
        $this->call('cgen:interface', ['name' => $interfaceName]);
    }

    /**
     * Create the repository file.
     *
     * @param string $name
     */
    protected function createRepository($name)
    {
        $repositoryName = $name . 'Repository';
        $this->call('cgen:repository', ['name' => $repositoryName]);
    }

    /**
     * Create the model file.
     *
     * @param string $modelName
     */
    protected function createModel($modelName)
    {
        $this->call('make:model', ['name' => $modelName]);
    }

    /**
     * Create the migration file.
     *
     * @param string $name
     */
    protected function createMigration($name)
    {
        $tableName = Str::snake(Str::pluralStudly($name));
        $this->call('make:migration', ['name' => 'create_' . $tableName . '_table']);
    }

    /**
     * Create the controller file.
     *
     * @param string $name
     */
    protected function createController($name)
    {
        $controllerName = $name . 'Controller';
        $this->call('cgen:controller', ['name' => $controllerName]);
    }

    /**
     * Create the create, update, and filter request files for the controller.
     *
     * @param string $name
     */
    protected function createRequests($name)
    {
        $requestTypes = ['Filter', 'Store', 'Update', 'GetAll'];

        foreach ($requestTypes as $type) {
            $requestName = $name . $type . 'Request';
            $this->call('cgen:request', ['name' => $requestName]);
        }
    }

    /**
     * Create the policy file.
     *
     * @param string $name
     */
    protected function createPolicy($name)
    {
        $policyName = $name . 'Policy';
        $this->call('cgen:policy', ['name' => $policyName]);
    }
}
