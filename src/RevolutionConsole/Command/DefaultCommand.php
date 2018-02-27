<?php

namespace RevolutionConsole\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use RevolutionConsole\FileHelper\CSVHelper;

class DefaultCommand extends Command
{
    private $is_first_time = true;

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
        $input_csv = $input->getArgument('csv-input');
        $output_csv = $input->getArgument('csv-output');

        // Step 1: Attempt to read the input file
        if(file_exists($input_csv))
            {
                $plu_totals = CSVHelper::processTotals($input_csv);
                if($plu_totals === false)
                    $io->error('Could note open file: '.$input_csv);
                else
                    {
                        echo var_dump($plu_totals);
                    }
            }
        else
            $io->error('File does not exist: '.$input_csv);
    }
}