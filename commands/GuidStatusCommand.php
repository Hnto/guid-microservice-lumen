<?php
namespace Guid;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class GuidStatusCommand extends BaseCommand
{

    protected function configure()
    {
        $this
            ->setName('status')
            ->setDescription('Shows the status of the current GUID installation')
            ->setHelp('This command shows you the status of the current GUID installation with all the containers, their endpoints etc.)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->checkGuidExistence();

        $process = new Process("docker ps -a -q -f name=" . $this->projectName ."");
        $process->run();
        $containers = $process->getOutput();
        $containers = explode("\n", $containers);

        $rows = [];
        foreach ($containers as $container) {
            $inspect = new Process("docker inspect --format='{{json .}}' " . $container);
            $inspect->run();
            $data = json_decode($inspect->getOutput(), true);

            $status = $data['State']['Status'];
            $name = $data['Config']['Image'];

            $webHttpEndpoint = '';
            $webHttpsEndpoint = '';
            $adminerHttpEndpoint = '';

            $ports = $data['NetworkSettings']['Ports'];
            if (!empty($ports)) {
                if (isset($ports['443/tcp'])) {
                    $webHttpsEndpoint = $ports['443/tcp'][0]['HostIp'] . ':' . $ports['443/tcp'][0]['HostPort'];
                }
                if (isset($ports['80/tcp'])) {
                    $webHttpEndpoint = $ports['80/tcp'][0]['HostIp'] . ':' . $ports['80/tcp'][0]['HostPort'];
                }
                if (isset($ports['8080/tcp'])) {
                    $adminerHttpEndpoint = $ports['8080/tcp'][0]['HostIp'] . ':' . $ports['8080/tcp'][0]['HostPort'];
                }
            }

            $endpoint = '';
            switch ($name) {
                case "guid_nginx":
                    $endpoint = $webHttpsEndpoint . ' / ' . $webHttpEndpoint;
                    break;
                case "adminer":
                    $endpoint = '' . ' / ' . $adminerHttpEndpoint;
                    break;
            }

            $rows[] = [$name, $status, $endpoint];
        }

        $this->buildTable(
            ['Container name', 'Status', 'Public endpoint (https / http)'],
            $rows
        );

        return 0;
    }
}
