<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address_churn', function (Blueprint $table) {
            $table->id();
            $table->string('street_address');
            $table->string('zip_code', 10);
            $table->text('store_types');
            $table->unsignedInteger('total_auth_count');
            $table->unsignedInteger('deauth_count');
            $table->decimal('address_history_years', 5, 1);
            $table->string('churn_tier');
            $table->text('store_names');
            $table->timestamps();

            $table->unique(['street_address', 'zip_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address_churn');
    }
};
