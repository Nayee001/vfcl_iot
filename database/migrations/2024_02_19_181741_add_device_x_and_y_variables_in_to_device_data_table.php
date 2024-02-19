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
            $table->string('entry_timestamps')->nullable()->after('health_status')->comment('Timestamps of data entries');
            $table->string('measurement_values')->nullable()->after('entry_timestamps')->comment('Values corresponding to the timestamps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_data', function (Blueprint $table) {
            $table->dropColumn('entry_timestamps')->nullable()->after('health_status')->comment('X - Timestamps of data entries');
            $table->dropColumn('measurement_values')->nullable()->after('entry_timestamps')->comment('Y - Values corresponding to the timestamps');
        });
    }
};
