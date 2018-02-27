<?php

namespace RevolutionConsole\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\Table;
use RevolutionConsole\FileHelper\CSVHelper;

class DefaultCommand extends Command
{
    private $is_first_time = true;

    protected function configure()
    {
        $this->setName('console.php')
        ->setDescription('Calculates the total value of each PLU sold over the time period provided in the source CSV document.')
        ->setHelp('Calculates the total value of each PLU sold over the time period provided in the source CSV document.')
        ->addArgument('CSV File Name', InputArgument::REQUIRED, 'The file name of the CSV file to process e.g. "PLU Data.csv"');
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
        $input_csv = $input->getArgument('CSV File Name');
        $io = new SymfonyStyle($input, $output);

        // Step 1: Attempt to read the input file
        if(file_exists($input_csv))
            {
                // Step 2: Process the PLU totals
                $plu_totals = CSVHelper::processTotals($input_csv);
                if($plu_totals === false)
                    $io->error('Could note open file: '.$input_csv);
                else if(count($plu_totals) == 0)
                    $io->error('No results were found');
                else
                    {
                        $io->text(sprintf('Processed data for %d unique PLU codes from CSV file "%s"', count($plu_totals), $input_csv));
                        $io->newLine();

                        // Step 3: Output the data visually
                        $table = new Table($output);
                        $table->setHeaders(array('PLU', 'Total Value'))->setRows($plu_totals);
                        $table->setStyle('borderless');
                        $table->render();
                        $io->newLine();

                        // Step 4: Save the data to a CSV file
                        if($io->confirm('Would you like to save this information to a CSV document?', true))
                            {
                                $file_name_invalid = true;
                                $file_name_suffix_int = 2;
                                $file_name_suffix = '';
                                $file_saved_successfully = false;
                                while($file_name_invalid)
                                    {
                                        $io->newLine();
                                        $output_csv = $io->ask('Please enter a filename', 'PLU_Totals'.$file_name_suffix.'.csv');

                                        if(file_exists($output_csv))
                                            {
                                                $option_1 = 'Choose a new file name';
                                                $option_2 = 'Overwrite it with the new data';
                                                $option_3 = 'Append the new data to the end';
                                                $file_option = $io->choice('That file already exists, what would you like to do?', array($option_1, $option_2, $option_3), $option_1);

                                                switch($file_option)
                                                    {
                                                        case $option_1:
                                                            $file_name_suffix = '_'.$file_name_suffix_int++;
                                                            $output_csv = '';
                                                            break;
                                                        case $option_2:
                                                            $file_saved_successfully = CSVHelper::Write($output_csv, $plu_totals);
                                                            $file_name_invalid = false;
                                                            break;
                                                        case $option_3:
                                                            $file_saved_successfully = CSVHelper::Write($output_csv, $plu_totals, 'a');
                                                            $file_name_invalid = false;
                                                            break;
                                                    }
                                            }
                                        else
                                            {
                                                $file_saved_successfully = CSVHelper::Write($output_csv, $plu_totals);
                                                $file_name_invalid = false;
                                            }
                                    }

                                if($file_saved_successfully)
                                    $io->success('File saved!');
                                else
                                    $io->error('Error saving file!');
                            }

                        $io->newLine();
                        $io->section(sprintf(' Thank you for using the %s! ', $this->getApplication()->getName()));
                    }
            }
        else
            $io->error('File does not exist: '.$input_csv);
    }
}