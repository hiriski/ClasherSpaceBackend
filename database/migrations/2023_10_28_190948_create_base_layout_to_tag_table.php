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
        Schema::create('base_layout_to_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('baseLayoutId');
            $table->unsignedBigInteger('tagId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_layout_to_tag');
    }
};
