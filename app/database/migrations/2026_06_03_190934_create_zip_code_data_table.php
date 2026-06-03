<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zip_code_data', function (Blueprint $table) {
            $table->string('zip_code', 10)->primary();
            $table->string('borough');
            $table->unsignedInteger('population');
            $table->decimal('median_household_income', 12, 2)->nullable();
            $table->string('income_bracket')->nullable();
            $table->unsignedTinyInteger('pct_below_poverty')->nullable();
            $table->string('poverty_tier');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zip_code_data');
    }
};
