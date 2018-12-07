<?php
namespace Guid;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class StopContainersCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('container:stop')
            ->setDescription('Stops all the running containers but does not remove them')
            ->setHelp('This command allows you to stop all the running container (for maintenance etc.)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->checkGuidExistence();

        /** Check if install has already been completed */
        if (!file_exists($this->basePath . '.env') || !file_exists($this->basePath . 'src/.env')) {
            $this->sendErrorMessage('GUID has not been installed yet');
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Are you sure you wish to stop all containers?', false);

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $this->setupProgressBar();

        $this->buildInfoMessage('Stopping containers');
        $process = new Process('docker stop $(docker ps -a -q -f name=' . $this->projectName . ')');
        $process->start();

        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->buildSuccessMessage('All the containers have successfully been stopped');
            $this->progressBar->finish();
        } else {
            $this->sendErrorMessage('could not stop (all) docker containers');
        }

        return 0;
    }
}
