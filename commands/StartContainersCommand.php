<?php
namespace Guid;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class StartContainersCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('container:start')
            ->setDescription('Starts the docker containers')
            ->setHelp('This command allows you to start all the dockers containers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->checkGuidExistence();

        $this->setupProgressBar();

        $this->buildInfoMessage('Starting containers');
        $process = new Process('docker-compose up -d --build');
        $process->start();

        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->buildSuccessMessage('All the containers have successfully been started');
            $this->progressBar->finish();
        } else {
            $this->sendErrorMessage('could not start (all) docker containers');
        }

        return 0;
    }
}
