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
            // Add new JSON field for event data
            $table->json('event_data')->nullable();

            // Drop unnecessary fields
            $table->dropColumn('device_timestamps');
            $table->dropColumn('valts');
            $table->dropColumn('current_phase1');
            $table->dropColumn('current_phase2');
            $table->dropColumn('current_phase3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_data', function (Blueprint $table) {
            // Remove the event_data JSON field
            $table->dropColumn('event_data');

            // Restore the dropped columns
            $table->timestamp('device_timestamps')->nullable();
            $table->decimal('valts', 8, 2)->nullable();
            $table->decimal('current_phase1', 8, 2)->nullable();
            $table->decimal('current_phase2', 8, 2)->nullable();
            $table->decimal('current_phase3', 8, 2)->nullable();
        });
    }
};
