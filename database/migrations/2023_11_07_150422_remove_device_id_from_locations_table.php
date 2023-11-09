<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['device_id']); // The array should contain the exact column name

            // Drop the device_id column
            $table->dropColumn('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            // Add the device_id column back to the table
            $table->unsignedBigInteger('device_id')->nullable();

            // Assuming you want to restore the foreign key as well
            // The 'devices' table should have an 'id' column that is being referenced.
            $table->foreign('device_id')
                  ->references('id')
                  ->on('devices')
                  ->onDelete('set null'); // or 'cascade', 'restrict', 'no action', based on your previous constraint
        });
    }
};
