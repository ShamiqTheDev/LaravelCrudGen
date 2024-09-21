<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:request {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new v1 request class';

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
        $name = str_replace('Request', '', $name);
        $name = $this->singularizeIfNeeded($name);

        $requestClass = $name . 'Request';
        $directory = app_path('Http/Requests/v1');

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $requestClass . '.php';

        if ($this->files->exists($path)) {
            $this->error("Request {$requestClass} already exists at [$path].");
            return;
        }

        $this->createRequest($path, $name);
        $this->createException($name);
    }

    public function createRequest($path, $name) {
        $className = $name . 'Request';
        $exceptionName = $name . 'ValidationException';
        $tableName = Str::snake(Str::plural($name));

        $stub = $this->files->get('stubs/request.v1.stub');

        $stub = str_replace(
            [
                '{{ class }}',
                '{{ class }}ValidationException',
                '{{ table }}'
            ],
            [
                $name,
                $exceptionName,
                $tableName
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->info("Request [app/Http/Requests/v1/{$className}.php] created successfully.");
    }

    public function createException($name) {
        $exceptionName = $name . 'ValidationException';
        $this->call('make:exception:v1', ['name' => $exceptionName]);
    }

    function singularizeIfNeeded($word)
    {
        $singular = Str::singular($word);
        return $word === $singular ? $word : $singular;
    }
}
