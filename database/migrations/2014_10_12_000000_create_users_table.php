<?php

use App\Enums\Status;
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
            $table->timestamp('emailVerifiedAt')->nullable();
            $table->string('password')->nullable();
            $table->string('photoUrl')->nullable();
            $table->string('avatarTextColor', 36)->nullable();
            $table->enum('gender', ['male', 'female', 'none'])->nullable();
            $table->text('about')->nullable();
            $table->date('dateOfBirthday')->nullable();
            $table->unsignedTinyInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE . '=' . 'Active' . ', ' . Status::INACTIVE . '=' . 'Inactive');
            $table->string('rememberToken', 100)->nullable();
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
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
