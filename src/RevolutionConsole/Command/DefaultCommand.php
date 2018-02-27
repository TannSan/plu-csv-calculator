<?php

namespace RevolutionConsole\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class DefaultCommand extends Command
{
    private $is_first_time;

    protected function configure()
    {
        $this->setName('plu:calculate')
        ->setDescription('Calculates the total value of each PLU sold over the time period provided in the source CSV document.')
        ->setHelp('Calculates the total value of each PLU sold over the time period provided in the source CSV document.')
        ->addArgument('csv-input', InputArgument::REQUIRED, 'The file name of the CSV file to process e.g. plu-data.csv')
        ->addArgument('csv-output', InputArgument::OPTIONAL, 'The file name of the CSV file to output e.g. plu-data-results.csv');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if($this->is_first_time)
            {
                $io = new SymfonyStyle($input, $output);
                $io->newLine();
                $io->title(sprintf(' Welcome to the %s ', $this->getApplication()->getName()));
                $this->is_first_time = false;
            }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}