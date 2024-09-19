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
            $table->string('current_phase1')->after('valts');
            $table->string('current_phase2')->after('current_phase1');
            $table->string('current_phase3')->after('current_phase2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_data', function (Blueprint $table) {
            $table->dropColumn('current_phase1')->after('valts');
            $table->dropColumn('current_phase2')->after('current_phase1');
            $table->dropColumn('current_phase3')->after('current_phase2');
        });
    }
};
