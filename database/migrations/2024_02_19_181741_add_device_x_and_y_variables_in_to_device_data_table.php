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
        Schema::table('device_data', function (Blueprint $table) {
            $table->string('device_timestamps')->nullable()->after('health_status')->comment('X - Timestamps of data entries');
            $table->string('valts')->nullable()->after('device_timestamps')->comment('Y - Valts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_data', function (Blueprint $table) {
            $table->dropColumn('device_timestamps')->nullable()->after('health_status')->comment('X - Timestamps of data entries');
            $table->dropColumn('valts')->nullable()->after('entry_timestamps')->comment('Y -  Valts Values corresponding to the timestamps');
        });
    }
};
