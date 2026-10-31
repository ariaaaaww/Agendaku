<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\SchoolEvent;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Users
        $siswa = User::firstOrCreate(
            ['username' => 'siswa'],
            [
                'name' => 'Arianto',
                'email' => 'siswa@agendaku.test',
                'password' => Hash::make('123'),
                'role' => 'siswa',
            ]
        );

        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@agendaku.test',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ]
        );

        $guru = User::firstOrCreate(
            ['username' => 'guru'],
            [
                'name' => 'Pak Guruh, M.Pd',
                'email' => 'guru@agendaku.test',
                'password' => Hash::make('123'),
                'role' => 'guru',
            ]
        );

        $osis = User::firstOrCreate(
            ['username' => 'osis'],
            [
                'name' => 'Pengurus OSIS',
                'email' => 'osis@agendaku.test',
                'password' => Hash::make('123'),
                'role' => 'osis',
            ]
        );

        // 2. Seed Default Tasks
        if (Task::count() === 0) {
            Task::create([
                'user_id' => $siswa->id,
                'created_by_id' => $guru->id,
                'title' => 'Tugas Matematika Bab 3',
                'category' => 'sekolah',
                'deadline' => Carbon::tomorrow()->toDateString(),
                'priority' => 'tinggi',
                'is_completed' => false,
            ]);

            Task::create([
                'user_id' => $siswa->id,
                'created_by_id' => $siswa->id,
                'title' => 'Latihan Futsal',
                'category' => 'pribadi',
                'deadline' => Carbon::today()->toDateString(),
                'priority' => 'sedang',
                'is_completed' => true,
            ]);
        }

        // 3. Seed Default School Events
        if (SchoolEvent::count() === 0) {
            SchoolEvent::create([
                'user_id' => $osis->id,
                'title' => 'Classmeet Futsal & Seni',
                'description' => 'Kompetisi olahraga futsal dan pagelaran seni kesiswaan',
                'event_date' => Carbon::tomorrow()->toDateString(),
            ]);
        }

        // 4. Seed Default Announcement
        if (Announcement::count() === 0) {
            Announcement::create([
                'user_id' => $guru->id,
                'content' => 'Ujian Tengah Semester dimulai tanggal 25 mendatang. Diharapkan siswa mempersiapkan diri!',
                'is_active' => true,
            ]);
        }
    }
}
