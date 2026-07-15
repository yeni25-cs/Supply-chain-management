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
    Schema::table('suppliers', function (Blueprint $table) {

        $table->double('risk_score')->default(0);

    });
}

public function down(): void
{
    Schema::table('suppliers', function (Blueprint $table) {

        $table->dropColumn('risk_score');

    });
}
};
