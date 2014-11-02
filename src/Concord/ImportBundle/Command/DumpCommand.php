<?php
// src/Concord/ImportBundle/Command/DumpCommand.php
namespace Concord\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DumpCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('import:dump')
            ->setDescription('Egg someone');
            //->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            //->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $parser = $this->getContainer()->get('TrionDumpParser');
        $parser->setDumpFile($this->getContainer()->get('kernel')->getRootdir().'/cache/RiftData.zip'); //TODO Temporary, should be from an input somewhere.
        $parser->test();
        $output->writeln('I ran');
    }
}