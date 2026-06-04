<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressChurnSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('address_churn')->truncate();

        $path = database_path('nyc_address_churn.csv');
        $handle = fopen($path, 'r');

        fgetcsv($handle); // skip header row

        $chunk = [];
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            $chunk[] = [
                'street_address' => $row[0],
                'zip_code' => $row[1],
                'store_types' => $row[2],
                'total_auth_count' => (int) $row[3],
                'deauth_count' => (int) $row[4],
                'address_history_years' => (float) $row[5],
                'churn_tier' => $row[6],
                'store_names' => $row[7],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($chunk) === 500) {
                DB::table('address_churn')->insert($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            DB::table('address_churn')->insert($chunk);
        }

        fclose($handle);
    }
}
