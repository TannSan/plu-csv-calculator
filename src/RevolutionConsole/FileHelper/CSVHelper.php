<?php

namespace RevolutionConsole\FileHelper;

class CSVHelper
{
    /**
     * Calculates the PLU totals from the provided CSV file.
     */
    public static function processTotals(string $file_name)
        {
            // Had to add this as PHP was having issues understanding the line endings in the supplied CSV file.
            ini_set('auto_detect_line_endings', true);
            if (($file = @fopen($file_name, 'r')) !== false)
                {
                    $results = array();
                    $headers = fgetcsv($file, 0, ',');
                    while (($data = fgetcsv($file, 1000, ',')) !== false)
                        {
                            if(array_key_exists('plu_'.$data[1], $results))
                                $results['plu_'.$data[1]] = array($data[1], $results['plu_'.$data[1]][1] + (intval($data[2]) * floatval($data[3])));
                            else
                                $results['plu_'.$data[1]] = array($data[1], (intval($data[2]) * floatval($data[3])));
                        }
                    fclose($file);
                    return $results;
                }
            else
                return false;
        }

    /**
     * Outputs the PLU totals to CSV file.
     */
    public static function write(string $file_name, array $row_data, string $mode = 'w')
        {
            if(is_dir($file_name))
                return false;

            $file = fopen($file_name, $mode.'b');

            if($file === false)
                return false;

            if($mode === 'w')
                fputcsv($file, array('PLU', 'Total Value'));

            foreach ($row_data as $row)
                {
                    fputcsv($file, $row);
                }

            fclose($file);

            return true;
        }
}