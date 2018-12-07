<?php

namespace Guid;

use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BaseCommand extends Command
{

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var SymfonyStyle
     */
    protected $style;

    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * @var string
     */
    protected $projectName;

    /**
     * @var string
     */
    protected $applicationEnvironment;


    const NGINX_SUFFIX = '_nginx';
    const WORKSPACE_SUFFIX = '_workspace';
    const PHP_FPM_SUFFIX = '_php-fpm';
    const ADMINER_SUFFIX = '_adminer';
    const MYSQL_SUFFIX = '_mysql';


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadEnvAndSetupVars();

        $this->basePath = __DIR__ . '/../';
        $this->input = $input;
        $this->output = $output;
        $this->style = new SymfonyStyle($this->input, $this->output);

        return 0;
    }

    /**
     * - Load the env values
     *  - docker
     *  - application
     *
     * - Set required variables
     *  - projectName = COMPOSE_PROJECT_NAME (docker)
     *  - applicationEnvironment = APP_ENV (application)
     */
    protected function loadEnvAndSetupVars()
    {
        if (file_exists(__DIR__ . '/../.env')) {
            //Load docker env
            $dotEnv = new Dotenv(__DIR__ . '/../');
            $dotEnv->load();

            $this->projectName = getenv('COMPOSE_PROJECT_NAME');
        }

        if (file_exists(__DIR__ . '/../src/.env')) {
            //Load application env
            $dotEnv = new Dotenv(__DIR__ . '/../src/');
            $dotEnv->load();

            $this->applicationEnvironment = getenv('APP_ENV');
        }
    }

    /**
     * Checks if the guid application is installed or not
     * If not, an error is returned and the command is halted
     */
    protected function checkGuidExistence()
    {
        if (!file_exists(__DIR__ . '/../.env') || !file_exists(__DIR__ . '/../src/.env')) {
            $this->style->error('GUID has not been installed yet');
            exit;
        }
    }


    /**
     * Remove env files
     */
    protected function removeEnvFiles()
    {
        //Remove .env files
        unlink($this->basePath . '.env');
        unlink($this->basePath. 'src/.env');
    }

    /**
     * Remove the mysql folder
     */
    protected function removeMysqlData()
    {
        $this->removeFolder($this->basePath . 'mysql');
    }

    /**
     * Remove the logs folder
     */
    protected function removeLogs()
    {
       $this->removeFolder($this->basePath . 'logs');
    }

    /**
     * Remove folder recursively
     *
     * @param string $dir
     */
    protected function removeFolder($dir)
    {
        foreach (glob($dir) as $file) {
            if (is_dir($file)) {
                $this->removeFolder("$file/*");
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }

    /**
     * Setup the progress bar
     *
     * @param int $steps
     *
     * @return void
     */
    protected function setupProgressBar($steps = 0)
    {
        $this->progressBar = new ProgressBar($this->output, $steps);
        $this->progressBar->setBarCharacter('<fg=green>⚬</>');
        $this->progressBar->setEmptyBarCharacter("<fg=red>⚬</>");
        $this->progressBar->setProgressCharacter("<fg=green>➤</>");
    }

    /**
     * @param string $message
     * @return void
     */
    protected function sendErrorMessage(string $message)
    {
        $this->style->newLine();
        $this->style->error($message);
        $this->style->newLine();
        exit;
    }

    /**
     * @param string $message
     * @return void
     */
    protected function buildInfoMessage(string $message)
    {
        $this->style->newLine();
        $this->style->writeln('==> ' . $message . ' <==');
        $this->style->newLine();
    }

    /**
     * @param string $message
     * @return void
     */
    protected function buildSuccessMessage(string $message)
    {
        $this->style->newLine();
        $this->style->success($message);
        $this->style->newLine();
    }

    /**
     * @param $headers
     * @param $rows
     */
    protected function buildTable($headers, $rows)
    {
        $this->style->table($headers, $rows);
    }
}
