<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {module_name} {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $moduleName = $this->argument('module_name');
        $version = $this->argument('version');
        if ($moduleName === null) {
            $moduleName = $this->ask('What is the module name?');
            $moduleName = ucfirst($moduleName);
        }
        if ($version === null) {
            $version = $this->ask('What is the version?');
        }
        if ($this->runCommand('make:model', ['-m' => $moduleName, 'name' => $moduleName], $this->output) > 0) {
            $this->error("Model $moduleName failed to create");

            return CommandAlias::FAILURE;
        }
        if ($this->runCommand('make:repository', ['model' => $moduleName], $this->output) > 0) {
            $this->error("Repository $moduleName failed to create");

            return CommandAlias::FAILURE;
        }
        if ($this->runCommand('make:service', ['model' => $moduleName], $this->output) > 0) {
            $this->error("Service $moduleName failed to create");

            return CommandAlias::FAILURE;
        }
        if ($this->runCommand('make:api',
            ['model' => $moduleName, 'version' => $version], $this->output) > 0) {
            $this->error("Controller $moduleName failed to create");

            return CommandAlias::FAILURE;
        }
        $this->info("Module $moduleName created");

        return CommandAlias::SUCCESS;
    }
}
