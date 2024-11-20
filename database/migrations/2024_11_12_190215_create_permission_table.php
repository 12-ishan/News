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
        Schema::create('permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->unique();
            $table->unsignedBigInteger('permission_group_id')->nullable();
            $table->string('slug')->nullable();
            $table->tinyInteger('status');
            $table->integer('sortOrder');
            $table->timestamps();

            $table->foreign('permission_group_id')->references('id')->on('permission_group')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission');
    }
};
