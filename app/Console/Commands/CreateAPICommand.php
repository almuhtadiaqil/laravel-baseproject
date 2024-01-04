<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateAPICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api {model} {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API controller';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $apiName = $this->argument('model');
        $apiVersion = $this->argument('version');
        if ($apiName === null) {
            $apiName = $this->ask('What is the model name?');
            $apiName = ucfirst($apiName);
        }
        if ($apiVersion === null) {
            $apiVersion = $this->ask('What is the version?');
            $apiVersion = ucfirst($apiVersion);
        }
        $apiPath = app_path("Http/Controllers/API/$apiVersion/$apiName".'Controller.php');
        if (file_exists($apiPath)) {
            $this->error("Controller $apiName already exists");

            return CommandAlias::FAILURE;
        }
        if (! file_exists(app_path("/Http/Controllers/API/$apiVersion"))) {
            // make directory
            mkdir(app_path("/Http/Controllers/API/$apiVersion"), 0777, true);
        }
        $controllerContent = file_get_contents(app_path('Console/Commands/templates/Controller.txt'));
        $controllerContent = str_replace('DummyModel', $apiName, $controllerContent);
        if (file_put_contents($apiPath, $controllerContent)) {
            $this->info("Controller $apiName created");
        } else {
            $this->error("Controller $apiName failed to create");

            return CommandAlias::FAILURE;
        }

        return CommandAlias::SUCCESS;
    }
}
