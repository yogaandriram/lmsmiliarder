<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin EduLux',
                'email' => 'admin@edulux.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'avatar_url' => null,
                'bio' => 'Administrator platform EduLux.',
            ],
            [
                'name' => 'Mentor EduLux',
                'email' => 'mentor@edulux.com',
                'password' => Hash::make('password'),
                'role' => 'mentor',
                'avatar_url' => null,
                'bio' => 'Mentor konten kursus.',
            ],
            [
                'name' => 'Member EduLux',
                'email' => 'member@edulux.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'avatar_url' => null,
                'bio' => 'Pengguna pembelajar.',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(['email' => $data['email']], $data);
        }
    }
}