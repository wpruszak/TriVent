<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('event:download');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start downloading activities');

        $activitiesService = $this->getContainer()->get('app.activities_download_service');
        $activitiesData = $activitiesService->getActivitiesDataArray();

        $output->writeln('Stop downloading activities');

    }
}
