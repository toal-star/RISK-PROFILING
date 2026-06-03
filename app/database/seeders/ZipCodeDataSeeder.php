<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZipCodeDataSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('zip_code_data')->truncate();

        $path = database_path('nyc_zip_code_data.csv');
        $handle = fopen($path, 'r');

        fgetcsv($handle); // skip header row

        $chunk = [];
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            $chunk[] = [
                'zip_code' => $row[0],
                'borough' => $row[1],
                'population' => (int) $row[2],
                'median_household_income' => $row[3] !== '' ? (float) $row[3] : null,
                'income_bracket' => $row[4] !== '' ? $row[4] : null,
                'pct_below_poverty' => $row[5] !== '' ? (int) $row[5] : null,
                'poverty_tier' => $row[6],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($chunk) === 500) {
                DB::table('zip_code_data')->insert($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            DB::table('zip_code_data')->insert($chunk);
        }

        fclose($handle);
    }
}
