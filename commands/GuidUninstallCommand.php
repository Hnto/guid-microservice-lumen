<?php
namespace Guid;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GuidUninstallCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('uninstall')
            ->setDescription('Uninstalls the GUID micro service application (removes .env files, mysql data and logs)')
            ->setHelp('This command uninstalls the GUID micro service application and deletes all the env files, mysql data and logs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->checkGuidExistence();

        //Execute container kill command
        $command = $this->getApplication()->find('container:kill');

        $returnCode = $command->run($input, $output);
        if ($returnCode !== 0) {
            $this->sendErrorMessage('An error occurred when killing the containers');
        }

        $this->removeEnvFiles();
        $this->removeMysqlData();
        $this->removeLogs();

        $this->buildSuccessMessage('Uninstall completed!');

        return 0;
    }
}
