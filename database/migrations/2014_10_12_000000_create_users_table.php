<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('avatar_text_color', 36)->nullable();
            $table->enum('gender', ['male', 'female', 'none'])->nullable();
            $table->text('about')->nullable();
            $table->date('date_of_birthday')->nullable();
            $table->enum('status', [User::STATUS_ACTIVE, User::STATUS_SUSPEND, User::STATUS_INACTIVE])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
