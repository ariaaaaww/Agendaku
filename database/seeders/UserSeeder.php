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
            [
                'name' => 'Teacher User',
                'username' => 'Guru',
                'password' => bcrypt('12345678'),
                'role' => 'teacher',
            ]
        );

        User::updateOrCreate(
            [
                'name' => 'Student User',
                'username' => 'Siswa',
                'password' => bcrypt('12345678'),
                'role' => 'student',
            ]
        );

        User::updateOrCreate(
            [
                'name' => 'Council User',
                'username' => 'Osis',
                'password' => bcrypt('12345678'),
                'role' => 'council',
            ]
        );

        User::updateOrCreate(
            [
                'name' => 'Admin User',
                'username' => 'Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
