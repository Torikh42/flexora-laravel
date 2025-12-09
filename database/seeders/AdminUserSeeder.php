<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminExists = User::where('email', 'admin@flexora.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin Flexora',
                'email' => 'admin@flexora.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@flexora.com');
            $this->command->info('Password: password');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
