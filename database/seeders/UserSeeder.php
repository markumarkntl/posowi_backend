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
                'nama'     => 'Admin POS',
                'name'     => 'Admin POS',
                'email'    => 'admin@pos.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'is_aktif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@pos.com'],
            [
                'nama'     => 'Kasir POS',
                'name'     => 'Kasir POS',
                'email'    => 'kasir@pos.com',
                'password' => Hash::make('password123'),
                'role'     => 'kasir',
                'is_aktif' => true,
            ]
        );

        $this->command->info('✅ User: admin@pos.com & kasir@pos.com / password123');
    }
}