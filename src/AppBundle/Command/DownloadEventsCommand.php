<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadEventsCommand extends Command
{
    protected function configure()
    {
        $this->setName('event:download');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $x = 2;
        $output->writeln('aaa');
    }
}
