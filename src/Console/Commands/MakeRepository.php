<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    // /**
    //  * The files directory instance.
    //  *
    //  * @var Filesystem
    //  */
    // protected $files;

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
        $modelName = str_replace('Repository', '', $name); // Correctly derive model name
        $interfaceName = $modelName . 'Interface';

        $repositoryDirectory = app_path('Repositories');

        if (!$this->files->isDirectory($repositoryDirectory)) {
            $this->files->makeDirectory($repositoryDirectory, 0755, true);
        }

        $repositoryPath = $repositoryDirectory . '/' . $name . '.php';

        if ($this->files->exists($repositoryPath)) {
            $this->error("Repository {$name} already exists at [app/Repositories/{$name}.php].");
            return;
        }

        $this->createRepository($repositoryPath, $name, $modelName, $interfaceName);

    }
    protected function getStub(string $stub)
    {
        return __DIR__ . "/../../stubs/" . $stub;
    }
    /**
     * Create the repository file.
     *
     * @param string $path
     * @param string $name
     * @param string $modelName
     * @param string $interfaceName
     */
    protected function createRepository($path, $name, $modelName, $interfaceName)
    {
        $repositoryStub = $this->files->get($this->getStub('repository.stub'));
        $repositoryStub = str_replace(
            ['{{ class }}', '{{ model }}', '{{ interface }}'],
            [$name, $modelName, $interfaceName],
            $repositoryStub
        );

        $this->files->put($path, $repositoryStub);
        $this->info("Repository [app/Repositories/{$name}.php] created successfully.");

        $this->alert("Now bind [$name.php] with [$interfaceName.php] in [app\Providers\AppServiceProvider.php].");

        $this->alert("\$this->app->bind($interfaceName::class, $name::class);");

    }

}
