<?php
namespace Guid;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class RestartContainersCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('container:restart')
            ->setDescription('Restarts the docker containers')
            ->setHelp('This command allows you to restart all the dockers containers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->checkGuidExistence();

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure you wish to RESTART all containers?', false);

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $this->setupProgressBar();

        $this->buildInfoMessage('Restarting docker containers');
        $process = new Process('docker stop $(docker ps -a -q -f name=' . $this->projectName . ') && docker-compose up -d --build');
        $process->start();

        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->buildSuccessMessage('All the containers have successfully been restarted');
            $this->progressBar->finish();
        } else {
            $this->sendErrorMessage('could not restart (all) docker containers');
        }

        return 0;
    }
}
