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
    Schema::table('ports', function (Blueprint $table) {

        $table->string('weather')->nullable();

        $table->decimal('temperature',8,2)->nullable();

        $table->decimal('rainfall',8,2)->nullable();

        $table->decimal('wind_speed',8,2)->nullable();

        $table->integer('risk_score')->default(0);

        $table->string('risk_status')->default('LOW');

        $table->timestamp('weather_updated_at')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('ports', function (Blueprint $table) {

        $table->dropColumn([
            'weather',
            'temperature',
            'rainfall',
            'wind_speed',
            'risk_score',
            'risk_status',
            'weather_updated_at'
        ]);

    });
}
};
