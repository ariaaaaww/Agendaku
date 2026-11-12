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
        // Guru
        User::updateOrCreate(
            ['username' => 'budi'],
            [
                'name'     => 'Budi',
                'password' => bcrypt('12345678'),
                'role'     => 'guru',
            ]
        );

        // Siswa
        User::updateOrCreate(
            ['username' => 'andi'],
            [
                'name'     => 'Andi',
                'password' => bcrypt('12345678'),
                'role'     => 'siswa',
            ]
        );
    }
}