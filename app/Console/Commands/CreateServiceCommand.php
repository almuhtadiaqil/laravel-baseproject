<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $serviceName = $this->argument('model');
        if ($serviceName === null) {
            $serviceName = $this->ask('What is the service name?');
            $serviceName = ucfirst($serviceName);
        }
        $servicePath = app_path("Services/$serviceName".'Service.php');
        if (file_exists($servicePath)) {
            $this->error("Service $serviceName already exists");

            return CommandAlias::FAILURE;
        }
        if (! file_exists(app_path('Services'))) {
            // make directory
            mkdir(app_path('Services'), 0777, true);
        }
        $serviceContent = file_get_contents(app_path('Console/Commands/templates/Service.txt'));
        $serviceContent = str_replace('DummyModel', $serviceName, $serviceContent);
        if (file_put_contents($servicePath, $serviceContent)) {
            $this->info("Service $serviceName created");
        } else {
            $this->error("Service $serviceName failed to create");

            return CommandAlias::FAILURE;
        }

        return CommandAlias::SUCCESS;
    }
}
