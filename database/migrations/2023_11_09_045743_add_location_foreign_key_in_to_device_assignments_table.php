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
        Schema::table('device_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable()->after('device_id');
            $table->foreign('location_id')->references('id')->on('location_names')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_assignments', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
        });
    }
};
