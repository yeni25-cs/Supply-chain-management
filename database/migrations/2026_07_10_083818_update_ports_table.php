<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('ports', function (Blueprint $table) {

        $table->string('name')->after('id');

        $table->string('country_code', 2)->after('name');

        $table->string('country_name')->nullable()->after('country_code');

        $table->decimal('latitude',10,6)->after('country_name');

        $table->decimal('longitude',10,6)->after('latitude');

        $table->string('city')->nullable()->after('longitude');

        $table->string('location')->nullable()->after('city');

        $table->string('type')->nullable()->after('location');

        $table->string('status')->default('Active')->after('type');

    });
}

    public function down(): void
{
    Schema::table('ports', function (Blueprint $table) {

        $table->dropColumn([
            'name',
            'country_code',
            'country_name',
            'latitude',
            'longitude',
            'city',
            'location',
            'type',
            'status'
        ]);

    });
}
};