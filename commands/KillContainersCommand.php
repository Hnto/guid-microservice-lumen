<?php
namespace Guid;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class KillContainersCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('container:kill')
            ->setDescription('Stops and removes all the docker containers')
            ->setHelp('This command allows you to stop and remove all the running container (mysql data and logs are preserved)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->setupProgressBar();

        $this->checkGuidExistence();

        $this->style->newLine();
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure you wish to STOP and REMOVE all containers?', false);

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $this->buildInfoMessage('Stopping containers');
        $process = new Process('docker stop $(docker ps -a -q -f name=' . $this->projectName . ')');
        $process->start();
        while ($process->isRunning()) {
            $this->progressBar->advance();
        }

        $this->buildInfoMessage('Removing containers');
        $process = new Process('docker rm $(docker ps -a -q -f name=' . $this->projectName . ')');
        $process->start();

        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->buildSuccessMessage('All the containers have successfully been stopped and removed');
            $this->progressBar->finish();
        } else {
            $this->sendErrorMessage('could not stop and remove (all) docker containers');
        }

        return 0;
    }
}
