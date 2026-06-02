<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disqualified_retailers', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('street_address');
            $table->string('borough')->nullable();
            $table->string('state', 2);
            $table->string('zip_code', 10);
            $table->string('case_type');
            $table->string('fad_date', 10)->comment('Final Action Date');
            $table->string('case_number');
            $table->string('outcome');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disqualified_retailers');
    }
};
