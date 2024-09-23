<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeService extends Command
{
    protected $signature = 'cgen:service {name}';
    protected $description = 'Create a new service class';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $name = $this->normalizeName($name);
        $path = $this->createService($name);

        if ($path) {
            $this->info("Service {$name} created successfully at [app/Services/{$name}.php]");
        }
    }

    protected function normalizeName(string $name): string
    {
        if (Str::endsWith($name, 'Service')) {
            $name = substr($name, 0, -7);
        }
        // Add the "Service" suffix
        return Str::studly(trim($name) . 'Service');
    }

    protected function createService(string $name)
    {
        $path = $this->getPath($name);

        if ($this->files->exists($path)) {
            $this->error("Service {$name} already exists at [app/Services/{$name}Service.php]!");
            return;
        }

        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub('service.stub'));
        $stub = $this->replaceClass($stub, $name);

        $this->files->put($path, $stub);

        return $path;
    }

    protected function getPath(string $name)
    {
        return app_path('Services/' . $name . '.php');
    }

    protected function getStub(string $stub)
    {
        return __DIR__ . "/../../stubs/" . $stub;
    }

    protected function makeDirectory(string $path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }
    }

    protected function replaceClass(&$stub, $name)
    {
        return str_replace('{{class}}', $name, $stub);
    }
}
