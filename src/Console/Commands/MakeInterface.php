<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeInterface extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:interface {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interfaceName = $this->argument('name');
        $interfacesDirectory = app_path('Interfaces');

        if (!$this->files->isDirectory($interfacesDirectory)) {
            $this->files->makeDirectory($interfacesDirectory, 0755, true);
        }

        $interfacePath = $interfacesDirectory . '/' . $interfaceName . '.php';

        if ($this->files->exists($interfacePath)) {
            $this->error("Interface {$interfaceName} already exists at [$interfacePath].");
            return;
        }

        $this->createInterface($interfacePath, $interfaceName);

    }
    protected function getStub(string $stub)
    {
        return __DIR__ . "/../../stubs/" . $stub;
    }
    /**
     * Create the interface file.
     *
     * @param string $path
     * @param string $interfaceName
     */
    protected function createInterface($path, $interfaceName)
    {
        $interfaceStub = $this->files->get($this->getStub('interface.stub'));
        $interfaceStub = str_replace(
            ['{{ interface }}'],
            [$interfaceName],
            $interfaceStub
        );

        $this->files->put($path, $interfaceStub);
        $this->info("Interface [app/Interfaces/{$interfaceName}.php] created successfully.");
    }

}
