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
        Schema::table('device_assingments', function (Blueprint $table) {
            $table->enum('status', ['Accept', 'Reject'])->default('Reject')->nullable();
            $table->string('encryption_key')->comment('deviceFolderKey')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_assingments', function (Blueprint $table) {
            $table->dropColumn('status', ['Accept', 'Reject'])->default('Reject');
            $table->dropColumn('encryption_key')->comment('deviceFolderKey');
        });
    }
};
