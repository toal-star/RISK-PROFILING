<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisqualifiedRetailerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('disqualified_retailers')->truncate();

        $path = database_path('nyc_snap_disqualified.csv');
        $handle = fopen($path, 'r');

        fgetcsv($handle); // skip header row

        $chunk = [];
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            $chunk[] = [
                'store_name' => $row[0],
                'street_address' => $row[1],
                'borough' => $row[2] !== '' ? $row[2] : null,
                'state' => $row[3],
                'zip_code' => $row[4],
                'case_type' => $row[5],
                'fad_date' => $row[6],
                'case_number' => $row[7],
                'outcome' => $row[8],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($chunk) === 500) {
                DB::table('disqualified_retailers')->insert($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            DB::table('disqualified_retailers')->insert($chunk);
        }

        fclose($handle);
    }
}
