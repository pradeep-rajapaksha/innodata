<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCustomersCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-customers-csv {filePath=exports/customers-data.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import of customer data into databse.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');
         
        if (file_exists($filePath)) {
            $this->error('Error: The file does not exist.');
            return Command::FAILURE;
        }

        $handle = @fopen($filePath, 'r');
        if (!$handle) {
            $this->error('Error: Failed to open file.');
            return Command::FAILURE;
        }

        $maxSize = 100 * 1024 * 1024; // 100MB in bytes
        if (fstat($handle) > $maxSize) {
            $this->error('Error: The file exceeds 100MB.');
            return Command::FAILURE;
        }

        if ($handle !== false) {
            $header = fgetcsv($handle, 1000, ',');

            $dataBatch = [];
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $dataBatch[] = array_combine($header, $row);

                if (count($dataBatch) === $this->batchSize) {
                    DB::table('customers')->insert($dataBatch);
                    $dataBatch = []; 
                }
            }

            if (!empty($dataBatch)) {
                DB::table('customers')->insert($dataBatch);
            }

            fclose($handle);

            return Command::SUCCESS;
        }
        else {
            $this->error('Something went wrong.');
            return Command::FAILURE;
        }
    }
}
