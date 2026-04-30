<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name'     => 'Admin POS',
                'email'    => 'admin@pos.com',
                'password' => Hash::make('password123'),
            ]
        );

        $this->command->info('✅ User admin berhasil dibuat: admin@pos.com');
    }
}