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
        Schema::create('base_layouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId')->nullable();
            $table->string('name')->nullable();
            $table->string('link');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('townHallLevel')->nullable();
            $table->unsignedTinyInteger('builderHallLevel')->nullable();
            $table->enum('baseType', ['townhall', 'builder'])->default('townhall');
            $table->text('imageUrls')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likedCount')->default(0);
            $table->boolean('markedAsWarBase')->default(false);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_layouts');
    }
};
