<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher User',
                'username' => 'Bapak Guru',
                'password' => bcrypt('12345678'),
                'role' => 'teacher',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'username' => 'Arianto',
                'password' => bcrypt('12345678'),
                'role' => 'student',
            ]
        );

        User::updateOrCreate(
            ['email' => 'council@example.com'],
            [
                'name' => 'Council User',
                'username' => 'Arianto WP',
                'password' => bcrypt('12345678'),
                'role' => 'council',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'Arianto Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
