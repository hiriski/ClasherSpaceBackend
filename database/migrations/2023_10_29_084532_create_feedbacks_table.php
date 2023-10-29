<?php

use App\Models\Feedback;
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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                Feedback::TYPE_BUG,
                Feedback::TYPE_IMPROVEMENT,
                Feedback::TYPE_MISSING_FEATURE,
                Feedback::TYPE_OTHER
            ])->default(Feedback::TYPE_OTHER);
            $table->string('email');
            $table->text('body');
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
