<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('retailers')->truncate();

        $path = database_path('nyc_snap_retailers.csv');
        $handle = fopen($path, 'r');

        fgetcsv($handle); // skip header row

        $chunk = [];
        $now = now();

        while (($row = fgetcsv($handle)) !== false) {
            $chunk[] = [
                'fns_record_id'  => $row[0],
                'store_name'     => $row[1],
                'store_type'     => $row[2],
                'street_address' => $row[3],
                'city'           => $row[4],
                'borough'        => $row[5] !== '' ? $row[5] : null,
                'zip_code'       => $row[6],
                'county'         => $row[7],
                'state'          => $row[8],
                'latitude'       => (float) $row[9],
                'longitude'      => (float) $row[10],
                'created_at'     => $now,
                'updated_at'     => $now,
            ];

            if (count($chunk) === 500) {
                DB::table('retailers')->insert($chunk);
                $chunk = [];
            }
        }

        if ($chunk !== []) {
            DB::table('retailers')->insert($chunk);
        }

        fclose($handle);
    }
}
