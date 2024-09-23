<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeTrait extends Command
{
    protected $signature = 'cgen:trait {name}';
    protected $description = 'Create a new trait';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $path = $this->createTrait($name);

        if ($path) {
            $this->info("Trait {$name} created successfully at [app/Traits/$name.php]");
        }
    }

    protected function createTrait(string $name)
    {
        // Determine the trait path
        $path = $this->getPath($name);

        // Check if the trait already exists
        if ($this->files->exists($path)) {
            $this->error("Trait {$name} already exists at [app/Traits/$name.php]!");
            return;
        }

        // Make directory if it doesn't exist
        $this->makeDirectory($path);

        // Replace stub placeholders with actual values
        $stub = $this->files->get($this->getStub('trait.stub'));

        // Replace namespace and class name
        $stub = $this->replaceNamespace($stub);
        $stub = $this->replaceClass($stub, $name);

        // Save the new trait file
        $this->files->put($path, $stub);

        return $path;

    }

    protected function getPath(string $name)
    {
        return app_path('Traits/' . $name . '.php');
    }

    protected function getStub(string $stub)
    {
        return __DIR__ . "/../../stubs/" . $stub;
    }

    protected function makeDirectory(string $path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }
    }

    protected function replaceNamespace(&$stub)
    {
        $namespace = 'App\\Traits';
        return str_replace('DummyNamespace', $namespace, $stub);
    }

    protected function replaceClass(&$stub, $name)
    {
        return str_replace('DummyClass', $name, $stub);
    }
}
