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
            $table->unsignedBigInteger('manager_id')->after('location_id');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_assignments', function (Blueprint $table) {
            $table->dropColumn('manager_id')->after('location_id');
        });
    }
};
