<?php

namespace Guid;

use Dotenv\Dotenv;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class UpdateGuidCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Updates GUID to the newest version (env value will be used: prod/local/dev)')
            ->setHelp('This command allows you to update the GUID code to the newest version');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        //$this->checkGuidExistence();

        $this->setupProgressBar();

        $this->style->newLine();
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Are you sure you wish to update GUID?',
                false);

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $this->fetchRepositoryInfo();

        if (!in_array($this->applicationEnvironment, ['production', 'prod', 'dev', 'development'])) {
            $this->sendErrorMessage(
                'The env value in your .env file is not valid (must be dev/development or prod/production).
                If you are running local, just update manually.'
            );
        }

        $this->updateToNewestVersion($this->applicationEnvironment);

        $this->buildSuccessMessage('GUID has been successfully updated.');

        return 0;
    }

    /**
     * Fetch repository information step
     */
    private function fetchRepositoryInfo()
    {
        $this->buildInfoMessage('Fetching new repository information..');
        $process = new Process('git fetch');
        $process->start();

        $this->progressBar->start(100);
        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->progressBar->finish();
        } else {
            $this->sendErrorMessage(
                'Could not fetch repository information  
                info: ' . $process->getOutput() . ' 
                error: ' . $process->getErrorOutput()
            );
        }
    }

    /**
     * Update GUID to the newest version
     *
     * @param string $env
     */
    private function updateToNewestVersion(string $env)
    {
        $this->buildInfoMessage('Updating ' . $env . ' to newest version');

        $command = '';

        switch ($env) {
            case 'production':
            case 'prod':
                $command = 'git pull origin master';
                break;

            case 'dev':
            case 'development':
                $command = 'git pull origin develop';
                break;
        }

        $process = new Process($command);
        $process->start();

        $this->progressBar->start(100);
        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->progressBar->finish();
        } else {
            $this->sendErrorMessage(
                'Could not update repository for ' . $env . '  
                info: ' . $process->getOutput() . ' 
                error: ' . $process->getErrorOutput()
            );
        }
    }
}
