<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:controller {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new controller';

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
        $name = str_replace('Controller', '', $name);
        $name = $this->singularizeIfNeeded($name);

        $namePluralised = $this->pluralizeIfNeeded($name);
        $controllerName = $namePluralised . 'Controller';
        $directory = app_path('Http/Controllers/Api');

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $controllerName . '.php';

        if ($this->files->exists($path)) {
            $this->error("Controller {$controllerName} already exists at [$path].");
            return;
        }

        $this->createController($path, $name);
        $this->createException($name);
    }

    protected function getStub(string $stub)
    {
        return __DIR__ . "/../../stubs/" . $stub;
    }

    public function createController($path, $name) {
        $modelName = Str::singular($name);
        $modelLowercase = Str::lower($modelName);
        $modelPlural = Str::plural($modelName);
        $modelPluralLowercase = Str::lower($modelPlural);

        $stub = $this->files->get($this->getStub('controller.api.v0.stub'));

        $stub = str_replace(
            [
                '{{ class }}',
                '{{ model }}',
                '{{ modelLowercase }}',
                '{{ modelPluralLowercase }}',
                '{{ modelPlural }}',
                '{{ ModelCreationFailedException }}'
            ],
            [
                $modelPlural . 'Controller',
                $modelName,
                $modelLowercase,
                $modelPluralLowercase,
                $modelPlural,
                $modelName . 'CreationFailedException'
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->info("Controller [app/Http/Controllers/Api/{$modelPlural}Controller.php] created successfully.");
    }

    public function createException($name) {
        $exceptionName = $name . 'CreationFailedException';
        $this->call('cgen:exception', ['name' => $exceptionName]);
    }

    function pluralizeIfNeeded($word)
    {
        $plural = Str::plural($word);
        return $word === $plural ? $word : $plural;
    }

    function singularizeIfNeeded($word)
    {
        $singular = Str::singular($word);
        return $word === $singular ? $word : $singular;
    }
}
