<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use App\Enums\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@clashers.space',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
            'status' => Status::ACTIVE,
            'emailVerifiedAt' => now(),
        ]);

        // Create General Users
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => Role::GENERAL_USER,
            'status' => Status::ACTIVE,
            'emailVerifiedAt' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => Role::GENERAL_USER,
            'status' => Status::ACTIVE,
            'emailVerifiedAt' => now(),
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'username' => 'mikejohnson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'role' => Role::GENERAL_USER,
            'status' => Status::ACTIVE,
            'emailVerifiedAt' => now(),
        ]);
    }
}
