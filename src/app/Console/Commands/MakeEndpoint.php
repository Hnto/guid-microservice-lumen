<?php

namespace App\Console\Commands;

class MakeEndpoint extends \Illuminate\Console\Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:endpoint {name}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new endpoint';

    /**
     * Handle the command
     */
    public function handle()
    {
        $name = ucwords($this->argument('name'));
        $namespace = str_replace('s', '', $name);

        $rootDir = realpath(__DIR__) . '/../../../';

        mkdir($rootDir . 'app/Core/' . $namespace);

        //Create endpoint
        $template = file_get_contents($rootDir . 'app/Api/Templates/Endpoint');
        $template = str_replace('{name}', $name, $template);

        $file = fopen($rootDir . 'app/Api/Endpoints/' . $name . '.php', 'w');
        fwrite($file, $template);
        fclose($file);

        //Create model
        $template = file_get_contents($rootDir . 'app/Core/Templates/Model');
        $template = str_replace('{namespace}', $namespace, $template);

        $file = fopen($rootDir . 'app/Core/' . $namespace . '/' . $namespace . 'Model.php', 'w');
        fwrite($file, $template);
        fclose($file);

        //Create transformer
        $template = file_get_contents($rootDir . 'app/Core/Templates/Transformer');
        $template = str_replace('{name}', $name, $template);
        $template = str_replace('{namespace}', $namespace, $template);
        $template = str_replace('{var}', '$' . strtolower($name), $template);

        $file = fopen($rootDir . 'app/Core/' . $namespace . '/' . $namespace . 'Transformer.php', 'w');
        fwrite($file, $template);
        fclose($file);
    }
}
