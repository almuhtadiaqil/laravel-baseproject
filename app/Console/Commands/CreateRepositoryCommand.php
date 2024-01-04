<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function PHPUnit\Framework\directoryExists;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // get model name from argument
        $modelName = $this->argument('model');
        if ($modelName === null) {
            $modelName = $this->ask('What is the model name?');
            $modelName = ucfirst($modelName);
        }
        $modelPath = app_path("Models/$modelName.php");
        if (! file_exists($modelPath)) {
            $this->error("Model $modelName not found");

            return 1;
        }
        if (! directoryExists(app_path("Repositories/$modelName"))) {
            // make directory
            mkdir(app_path("Repositories/$modelName"), 0777, true);
        }
        $repositoryInterfacePath = app_path("Repositories/$modelName/$modelName".'Interface.php');
        if (file_exists($repositoryInterfacePath)) {
            $this->error("Repository interface $modelName already exists");

            return 1;
        }
        $repositoryPathFolder = app_path("Repositories/$modelName");
        if (! file_exists($repositoryPathFolder) && ! is_dir($repositoryPathFolder)) {
            mkdir($repositoryPathFolder);
        }
        $repositoryInterfaceContent = file_get_contents(app_path('Console/Commands/templates/RepositoryInterface.txt'));
        $repositoryInterfaceContent = str_replace('DummyModel', $modelName, $repositoryInterfaceContent);
        if (file_put_contents($repositoryInterfacePath, $repositoryInterfaceContent)) {
            $this->info("Repository interface $modelName created");
        } else {
            $this->error("Repository interface $modelName failed to create");

            return 1;
        }
        $repositoryPath = app_path("Repositories/$modelName/$modelName".'Repository.php');
        if (file_exists($repositoryPath)) {
            $this->error("Repository $modelName already exists");

            return 1;
        }
        $repositoryContent = file_get_contents(app_path('Console/Commands/templates/Repository.txt'));
        $repositoryContent = str_replace('DummyModel', $modelName, $repositoryContent);
        if (file_put_contents($repositoryPath, $repositoryContent)) {
            $this->info("Repository $modelName created");
        } else {
            $this->error("Repository $modelName failed to create");

            return 1;
        }

        return CommandAlias::SUCCESS;
    }
}
