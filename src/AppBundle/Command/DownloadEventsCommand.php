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

        $activityService = $this->getContainer()->get('app.activity_download_service');
        $activityFactory = $this->getContainer()->get('app.activity_factory');

        $xmlData = $activityService->getXmlDataAsArray();

        foreach ($xmlData['channel']['item'] as $key => $value) {
            $activityDataArray = $activityService->getActivityDataArray($value);
            $activityFactory->createActivity($activityDataArray);
            if ($key % 10 == 0)  {
                $this->getContainer()->get('doctrine.orm.default_entity_manager')->clear();
            }
        }

        $output->writeln('Stop downloading activities');
    }
}
