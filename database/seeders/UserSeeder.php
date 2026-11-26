<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Administrator Utama
        User::firstOrCreate(
            ['email' => 'admin@kursusku.test'],
            [
                'name' => 'Administrator KursusKu',
                'password' => Hash::make('AdminKursusKu123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Akun Pengguna / Peserta Terdaftar
        User::firstOrCreate(
            ['email' => 'alya@example.test'],
            [
                'name' => 'Alya Rahma',
                'password' => Hash::make('UserKursusKu123!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'bima@example.test'],
            [
                'name' => 'Bima Putra',
                'password' => Hash::make('UserKursusKu123!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
