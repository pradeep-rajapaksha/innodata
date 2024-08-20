<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateCustomersCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-customers-csv {rows=1000 : Row count to be added}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate csv file of fake customer data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = 'exports/';
        $filename = 'customers-data.csv';
        $csvFile = tmpfile();
        $csvPath = stream_get_meta_data($csvFile)['uri'];

        $rows = $this->argument('rows');

        $handle = fopen($csvPath, 'w');

        fputcsv($handle, [
            'Name',
            'Email',
            'Contact Number',
            'Address',
            'Birthday',
        ]);

        for ($i=0; $i < $rows; $i++) { 
            $data = [
                fake()->name(),
                fake()->unique()->safeEmail(),
                fake()->phoneNumber(),
                preg_replace('~[\r\n\t]+~', '', fake()->address()),
                fake()->dateTimeBetween('-40 years', '-20 years')->format('Y-m-d'),
            ];

            fputcsv($handle, $data);
        }
        fclose($handle);
        // \Storage::disk('public')->putFileAs('', $csvPath, $path.strtotime('now').'.csv');
        \Storage::disk('public')->putFileAs('', $csvPath, $path.$filename);
    }
}
