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
        Schema::table('users', function (Blueprint $table) {
            $table->string('fname')->after('id')->nullable();
            $table->string('lname')->after('fname')->nullable();
            $table->string('title')->after('lname')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 -> In Active , 1 -> Active');
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fname')->after('id')->nullable();
            $table->dropColumn('lname')->after('fname')->nullable();
            $table->dropColumn('title')->after('lname')->nullable();
            $table->dropColumn('status')->default(0)->comment('0 -> In Active , 1 -> Active');
            $table->softDeletes();
        });
    }
};
