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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');
            $table->integer('submenu')->default(0)->comment('1=yes, 0=no');
            $table->string('title')->nullable();
            $table->integer('parent_menu_id')->nullable();
            $table->string('link')->nullable();
            $table->integer('sort')->nullable();
            $table->string('target')->default('_self')->nullable();
            $table->integer('status')->nullable()->default(0)->comment('1=Active, 0=InActive');;
            $table->string('icon')->nullable();
            $table->integer('menu_type')->default(2)->nullable()->comment('1=page,2=link,3=submenu');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
