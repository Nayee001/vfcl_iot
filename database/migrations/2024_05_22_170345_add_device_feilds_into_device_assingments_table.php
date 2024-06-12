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
            $table->timestamp('last_sync')->nullable()->after('updated_at');
            $table->boolean('send_mac')->default(false)->after('last_sync');
            $table->boolean('login_to_device')->default(false)->after('send_mac');
            $table->enum('connection_status', ['Active', 'Not Active'])->default('Not Active')->after('login_to_device');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_assignments', function (Blueprint $table) {
            $table->dropColumn('last_sync')->nullable()->after('updated_at');
            $table->dropColumn('send_mac')->default(false)->after('last_sync');
            $table->dropColumn('login_to_device')->default(false)->after('send_mac');
            $table->dropColumn('connection_status', ['Active', 'Not Active'])->default('Not Active')->after('login_to_device');
        });
    }
};
