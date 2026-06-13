<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Judge',
            'email' => 'judge@benefitguard.nyc',
            'password' => bcrypt('JudgeAccess2026'),
        ]);

        $this->call([
            RetailerSeeder::class,
            DisqualifiedRetailerSeeder::class,
            ZipCodeDataSeeder::class,
        ]);
    }
}
