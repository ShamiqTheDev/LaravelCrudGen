<?php

namespace ShamiqThedev\LaravelCrudGen\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakePolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cgen:policy {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new v1 policy class';

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
        $name = str_replace('Policy', '', $name);
        $name = $this->singularizeIfNeeded($name);

        $policyClass = $name . 'Policy';
        $directory = app_path('Policies/v1');

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $policyClass . '.php';

        if ($this->files->exists($path)) {
            $this->error("Policy {$policyClass} already exists at [$path].");
            return;
        }

        $this->createPolicy($path, $name);
        $this->generateSeederMessage($name);
    }

    public function createPolicy($path, $name)
    {
        $className = $name . 'Policy';
        $modelKebab = Str::kebab($name);

        $stub = $this->files->get('stubs/policy.v1.stub');

        $stub = str_replace(
            [
                '{{ class }}',
                '{{ model }}',
                '{{ model_kebab }}'
            ],
            [
                $className,
                $name,
                $modelKebab
            ],
            $stub
        );

        $this->files->put($path, $stub);
        $this->info("Policy [app/Policies/v1/{$className}.php] created successfully.");
    }

    function singularizeIfNeeded($word)
    {
        $singular = Str::singular($word);
        return $word === $singular ? $word : $singular;
    }

    public function generateSeederMessage($name)
    {
        $permissions = [
            'View Any', // index
            'Get All', // getAll
            'View', // show
            'Create', // store
            'Update', // update
            'Delete', // destroy
            'Restore', // restore
            'Force Delete', // forceDelete
        ];

        $seederMessage = "\n            // Permissions for {$name}Controller\n";
        foreach ($permissions as $permission) {
            $name = Str::kebab($name);
            $slug = Str::slug(strtolower("{$permission} {$name}"));
            $seederMessage .= "            [\n";
            $seederMessage .= "                'name' => '{$permission} {$name}',\n";
            $seederMessage .= "                'slug' => '{$slug}',\n";
            $seederMessage .= "                'description' => 'Grants user {$slug} access',\n";
            $seederMessage .= "                'created_at' => Carbon::now(),\n";
            $seederMessage .= "                'updated_at' => Carbon::now(),\n";
            $seederMessage .= "            ],\n";
        }

        $this->info("Add the following permissions to your [database/seeders/PermissionsTableSeeder]:");
        $this->line($seederMessage);
    }

}
