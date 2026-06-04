<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('retailers', function (Blueprint $table) {
            $table->unsignedTinyInteger('risk_score')->nullable()->after('longitude');
            $table->string('risk_tier')->nullable()->after('risk_score');
        });
    }

    public function down(): void
    {
        Schema::table('retailers', function (Blueprint $table) {
            $table->dropColumn(['risk_score', 'risk_tier']);
        });
    }
};
