<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;

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
        $csvContent = Storage::disk('public')->get($filePath);

        if (!$csvContent) {
            $this->error('Error: Failed to open file.');
            return Command::FAILURE;
        }

        $fileSize = Storage::disk('public')->size($filePath);
        $maxSize = 100 * 1024 * 1024; // 100MB in bytes
        if ($fileSize > $maxSize) {
            $this->error('Error: The file exceeds 100MB.');
            return Command::FAILURE;
        }

        if ($csvContent !== false) {
            $rows = array_map('str_getcsv', explode("\n", $csvContent));
            $header = ['name', 'email', 'contact', 'address', 'birthday'];
            array_shift($rows); // removing first row -> the header

            $chunks = array_chunk($rows, 100);

            foreach ($chunks as $chunk) {
                $dataBatch = [];
                foreach ($chunk as $item) {
                    if (empty($item[0]) Or empty($item[2])) {
                        continue;
                    }
                    $dataBatch[] = [
                        'name' => $item[0],
                        'email' => $item[1] ?? null,
                        'contact' => $item[2],
                        'address' => $item[3] ?? null,
                        'birthday' => $item[4] ?? null,
                    ];
                }
                // 
                try {
                    \DB::table('customers')->insert($dataBatch);
                } catch (\Throwable $th) {
                    $this->error('Error: '. $th->getMessage());
                    return Command::FAILURE;
                }
            }
            $this->error('Something went wrong.');
            return Command::SUCCESS;
        }
        else {
            $this->error('Something went wrong.');
            return Command::FAILURE;
        }
    }
}
