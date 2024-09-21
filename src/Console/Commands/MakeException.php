<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeException extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:exception {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new v1 exception class';

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
        $name = str_replace('Exception', '', $name);

        $exceptionClass = $name . 'Exception';
        $directory = app_path('Exceptions');

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $exceptionClass . '.php';

        if ($this->files->exists($path)) {
            $this->error("Exception {$exceptionClass} already exists at [$path].");
            return;
        }

        $this->createException($path, $name);
    }

    public function createException($path, $name)
    {
        $className = $name . 'Exception';

        $stub = $this->files->get('stubs/exception.v1.stub');

        $stub = str_replace(
            [
                '{{ class }}',
                '{{ message }}',
            ],
            [
                $className,
                "An error occurred",
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->info("Exception [app/Exceptions/{$className}.php] created successfully.");
    }
}
