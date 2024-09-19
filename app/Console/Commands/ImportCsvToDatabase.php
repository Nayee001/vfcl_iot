<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportCsvToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import {file}';
    protected $description = 'Import CSV data into the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Path to the CSV file
        $file = $this->argument('file');

        // Open and read the CSV file
        if (($handle = fopen($file, 'r')) !== FALSE) {
            // Read the header of the CSV file
            $header = fgetcsv($handle, 1000, ",");

            // Loop through the file and extract data
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Create an array with data to insert
                $insertData = [
                    'device_id' => 1, // Set the device id accordingly
                    'fault_status' => 'ON', // Placeholder, update based on logic
                    'topic' => 'mqttdevice/SEL-751/current-pass/valts', // Provided topic
                    'device_status' => 'active', // Placeholder, update based on logic
                    'health_status' => 'healthy', // Placeholder, update based on logic
                    'device_timestamps' => $data[0], // Assuming column 1 contains timestamps
                    'valts' => 0, // Assuming column 2 is valts
                    'current_phase1' => $data[1], // Assuming column 3 is current_phase1
                    'current_phase2' => $data[2], // Assuming column 4 is current_phase2
                    'current_phase3' => $data[3], // Assuming column 5 is current_phase3
                    'timestamp' => Carbon::now(), // Set current timestamp or data from CSV
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                // Insert the data into the database
                DB::table('device_data')->insert($insertData);
            }

            fclose($handle);
            $this->info('Data has been successfully imported.');
        } else {
            $this->error('Failed to open the file.');
        }
    }
}
