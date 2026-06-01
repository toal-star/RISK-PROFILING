<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retailers', function (Blueprint $table) {
            $table->id();
            $table->string('fns_record_id')->unique();
            $table->string('store_name');
            $table->string('store_type');
            $table->string('street_address');
            $table->string('city');
            $table->string('borough')->nullable();
            $table->string('zip_code', 10);
            $table->string('county');
            $table->string('state', 2);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 11, 7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retailers');
    }
};
