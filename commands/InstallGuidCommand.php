<?php
namespace Guid;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

class InstallGuidCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Installs the guid micro service application')
            ->setHelp('This command allows you to do a first time install of the guid generator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Execute parent function
        parent::execute($input, $output);

        /** Check if install has already been completed */
        if (file_exists($this->basePath . '.env') && file_exists($this->basePath . 'src/.env')) {
            $this->style->error('GUID has already been installed');
            return 1;
        }

        $this->setupProgressBar();

        /** First step -- copy .env.example files to .env and setup project name */
        $this->copyExampleEnvToEnv();
        //Load the env files and setup vars
        $this->loadEnvAndSetupVars();

        /** First step -- setup environment variables */
        //First question -- choose environment
        $this->chooseEnvironment($this->getHelper('question'));
        //Second question -- fill in database password
        $this->chooseDatabasePasswords($this->getHelper('question'));

        /** Second step -- build and start docker containers */
        $this->startDockerContainers();

        /** Third step -- setup application */
        $this->setupApplication();

        $this->progressBar->finish();

        $hostsAndPorts = $this->extractHostsAndPorts();
        $this->buildSuccessMessage('GUID has been successfully installed. ');
        $this->buildTable(
            ['Container', 'Public endpoint'],
            $hostsAndPorts
        );

        return 0;
    }

    /**
     * Copy example env's to env
     */
    private function copyExampleEnvToEnv()
    {
        if (copy($this->basePath . '.env.example', '.env') === false) {
            $this->removeEnvFiles();
            $this->sendErrorMessage(
                'could not copy env examples to env file, install failed. Please modify the changes manually'
            );
        }

        if (copy($this->basePath . 'src/.env.example', 'src/.env') === false) {
            $this->removeEnvFiles();
            $this->sendErrorMessage(
                'could not copy env examples to env file, install failed. Please modify the changes manually'
            );
        }
    }

    /**
     * @param QuestionHelper $questionHelper
     * @return bool
     */
    private function chooseEnvironment(QuestionHelper $questionHelper)
    {
        $question = new ChoiceQuestion(
            'Please select the application environment',
            ['local', 'development', 'production'],
            0
        );
        $question->setErrorMessage('Environment %s is invalid.');

        $environment = $questionHelper->ask($this->input, $this->output, $question);
        $debug = 'true';
        switch ($environment) {
            case 'local':
                $debug = 'true';
                break;
            case 'development':
                $debug = 'true';
                break;
            case 'production':
                $debug = 'false';
                break;
        }

        $path = $this->basePath . 'src/.env';

        if (file_put_contents($path, str_replace(
            'APP_ENV=local', 'APP_ENV='.$environment, file_get_contents($path)
        )) === false) {
            $this->removeEnvFiles();
            $this->sendErrorMessage('could not alter environment, install failed. Please modify the changes manually');
        }

        if (file_put_contents($path, str_replace(
            'APP_DEBUG=true', 'APP_DEBUG='.$debug, file_get_contents($path)
        )) === false) {
            $this->removeEnvFiles();
            $this->sendErrorMessage('could not alter app debug, install failed. Please modify the changes manually');
        }

        return true;
    }

    /**
     * @param QuestionHelper $questionHelper
     * @return void
     */
    private function chooseDatabasePasswords(QuestionHelper $questionHelper)
    {
        $question = new Question(
            'Please enter the password for the guid username of the database (or leave empty for default "secret"):',
            'secret'
        );
        $this->style->newLine();
        $userPassword = $questionHelper->ask($this->input, $this->output, $question);

        $question = new Question(
            'Please enter the root password of the database (or leave empty for default "secret"):',
                'secret'
        );
        $this->style->newLine();
        $rootPassword = $questionHelper->ask($this->input, $this->output, $question);

        $appPath = $this->basePath . 'src/.env';
        $installPath = $this->basePath . '.env';

        if (file_put_contents($appPath, str_replace(
            'DATABASE_URL=mysql://guid:secret@mysql:3306/guid',
            'DATABASE_URL=mysql://guid:'. $userPassword .'@mysql:3306/guid',
                    file_get_contents($appPath)
            )) === false) {
                $this->removeEnvFiles();
                $this->sendErrorMessage('could not alter DATABASE_URL env, install failed. Please modify the changes manually');
        }

        if (file_put_contents($installPath, str_replace(
                'DB_ROOT_PASS=secret', 'DB_ROOT_PASS='.$rootPassword, file_get_contents($installPath)
            )) === false) {
            $this->removeEnvFiles();
            $this->sendErrorMessage('could not alter DB_ROOT_PASS env, install failed. Please modify the changes manually');
        }

        if (file_put_contents($installPath, str_replace(
                'DB_PASS=secret', 'DB_PASS='.$rootPassword, file_get_contents($installPath)
            )) === false) {
            $this->removeEnvFiles();
            $this->sendErrorMessage('could not alter DB_ROOT_PASS env, install failed. Please modify the changes manually');
        }
    }

    /**
     * Start docker containers
     *
     * @return void
     */
    private function startDockerContainers()
    {
        $this->buildInfoMessage('Starting the docker containers');
        $process = new Process('docker-compose up -d --build');
	$process->setTimeout(0);
        $process->start();
        $this->progressBar->start();

        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->progressBar->finish();
        } else {
            $this->removeEnvFiles();
            $this->sendErrorMessage($process->getErrorOutput());
        }
    }

    /**
     * Setup the application by doing:
     * - database migrations
     * - database seeding
     * - ...
     *
     * @return void
     */
    private function setupApplication()
    {
        $this->buildInfoMessage('Waiting for composer install to finish..');

        $this->progressBar->start(1000000);

        $loops = 0;
        do {
            if ($loops > 1000000) {
                $this->sendErrorMessage(
                    'Waiting for composer install took too long, please proceed with manual install'
                );
            }
            if (file_exists("src/vendor/autoload.php")) {
                $this->progressBar->finish();
                break;
            } else {
                $this->progressBar->advance();
            }
            $loops++;
        } while(true);

        //Database migrations
        $this->buildInfoMessage('Running database migrations');
        $process = new Process('docker exec ' . $this->projectName . self::WORKSPACE_SUFFIX . ' php artisan --no-interaction migrate');
        $process->start();
        $this->progressBar->start(0);

        $process->wait(function () {
            $this->progressBar->advance();
        });

        if ($process->isSuccessful()) {
            $this->progressBar->finish();
        } else {
            $this->removeEnvFiles();
            $this->sendErrorMessage($process->getOutput() . $process->getErrorOutput());
        }
    }

    /**
     * @return array
     */
    private function extractHostsAndPorts() : array
    {
        $inspect = new Process(
            "docker inspect --format='{{json .NetworkSettings.Ports}}' " . $this->projectName . self::NGINX_SUFFIX . ""
        );
        $inspect->run();
        $nginx = json_decode($inspect->getOutput(), true);

        $inspect = new Process(
            "docker inspect --format='{{json .NetworkSettings.Ports}}' " . $this->projectName . self::ADMINER_SUFFIX . ""
        );
        $inspect->run();
        $adminer = json_decode($inspect->getOutput(), true);

        return [
            ['NGINX HTTP', $nginx['80/tcp'][0]['HostIp'] . ':' . $nginx['80/tcp'][0]['HostPort']],
            ['NGINX HTTPS', $nginx['443/tcp'][0]['HostIp'] . ':' . $nginx['443/tcp'][0]['HostPort']],
            ['ADMINER HTTP', $adminer['8080/tcp'][0]['HostIp'] . ':' . $adminer['8080/tcp'][0]['HostPort']]
        ];
    }
}
