@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Panel Admin')

@section('content')
    <section id="page-dashboard">
        <!-- Tab Kalender, To-Do List, Acara Sekolah, Tambah Agenda -->
        @include('components.dashboard-views')

        <!-- TAB 5: PANEL ADMIN -->
        <div id="view-role-panel" class="hidden">
            <h2 class="text-2xl font-extrabold mb-6 tracking-tight">Panel Kontrol Administrator</h2>
            <div class="stats-grid grid grid-cols-[repeat(auto-fit,minmax(220px,1fr))] gap-5 mb-8">
                <div class="stat-card bg-bg-card border-2 border-text-dark rounded-2xl p-6 shadow-pop-5px">
                    <span class="text-xs text-text-muted font-extrabold uppercase tracking-wider">Total Pengguna
                        (Siswa)</span>
                    <h3 id="admin-stat-users" class="text-4xl font-extrabold mt-1.5 text-primary-orange tracking-tight">0</h3>
                </div>
                <div class="stat-card bg-bg-card border-2 border-text-dark rounded-2xl p-6 shadow-pop-5px">
                    <span class="text-xs text-text-muted font-extrabold uppercase tracking-wider">Total Tugas Selesai</span>
                    <h3 id="admin-stat-completed" class="text-4xl font-extrabold mt-1.5 text-cat-pribadi tracking-tight">0
                    </h3>
                </div>
                <div class="stat-card bg-bg-card border-2 border-text-dark rounded-2xl p-6 shadow-pop-5px">
                    <span class="text-xs text-text-muted font-extrabold uppercase tracking-wider">Total Tugas Pending</span>
                    <h3 id="admin-stat-pending" class="text-4xl font-extrabold mt-1.5 text-prio-tinggi tracking-tight">0
                    </h3>
                </div>
            </div>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Progres Tugas Per Siswa</div>
                <div class="table-responsive w-full overflow-x-auto rounded-xl border-2 border-border-custom">
                    <table class="admin-table w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-bg-main font-extrabold text-text-dark uppercase text-xs tracking-wider">
                                <th class="p-4.5 border-b border-border-custom">Nama Siswa</th>
                                <th class="p-4.5 border-b border-border-custom">Tugas Selesai</th>
                                <th class="p-4.5 border-b border-border-custom">Tugas Pending</th>
                                <th class="p-4.5 border-b border-border-custom">Total Tugas</th>
                            </tr>
                        </thead>
                        <tbody id="admin-user-table-body" class="[&_tr:hover_td]:bg-primary-light"></tbody>
                    </table>
                </div>
            </div>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Daftar Acara OSIS & Sekolah</div>
                <div class="table-responsive w-full overflow-x-auto rounded-xl border-2 border-border-custom">
                    <table class="admin-table w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-bg-main font-extrabold text-text-dark uppercase text-xs tracking-wider">
                                <th class="p-4.5 border-b border-border-custom">Judul Acara</th>
                                <th class="p-4.5 border-b border-border-custom">Tanggal Pelaksanaan</th>
                                <th class="p-4.5 border-b border-border-custom">Penyelenggara</th>
                            </tr>
                        </thead>
                        <tbody id="admin-events-table-body" class="[&_tr:hover_td]:bg-primary-light"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function renderAdminData() {
            const users = JSON.parse(localStorage.getItem('users')) || [];
            const tasks = JSON.parse(localStorage.getItem('tasks')) || [];

            const siswas = users.filter(u => u.role === 'siswa');
            const completedTasks = tasks.filter(t => t.completed).length;
            const pendingTasks = tasks.filter(t => !t.completed && t.category !== 'acara').length;

            const uStat = document.getElementById('admin-stat-users');
            const cStat = document.getElementById('admin-stat-completed');
            const pStat = document.getElementById('admin-stat-pending');

            if (uStat) uStat.innerText = siswas.length;
            if (cStat) cStat.innerText = completedTasks;
            if (pStat) pStat.innerText = pendingTasks;

            const userTable = document.getElementById('admin-user-table-body');
            if (userTable) {
                userTable.innerHTML = '';
                siswas.forEach(siswa => {
                    const uTasks = tasks.filter(t => t.userId === siswa.id);
                    const uDone = uTasks.filter(t => t.completed).length;
                    const uPending = uTasks.filter(t => !t.completed).length;

                    userTable.innerHTML += `
          <tr class="border-b border-border-custom">
            <td class="p-4.5 font-extrabold">${siswa.fullname} (${siswa.username})</td>
            <td class="p-4.5 font-extrabold text-cat-pribadi">${uDone}</td>
            <td class="p-4.5 font-extrabold text-prio-tinggi">${uPending}</td>
            <td class="p-4.5 font-bold"><strong>${uTasks.length}</strong></td>
          </tr>
        `;
                });
            }

            const eventsTable = document.getElementById('admin-events-table-body');
            if (eventsTable) {
                eventsTable.innerHTML = '';
                const events = tasks.filter(t => t.category === 'acara');
                events.forEach(ev => {
                    eventsTable.innerHTML += `
          <tr class="border-b border-border-custom">
            <td class="p-4.5 font-extrabold">${ev.title}</td>
            <td class="p-4.5">${ev.deadline}</td>
            <td class="p-4.5">${ev.createdBy || 'OSIS'}</td>
          </tr>
        `;
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderAdminData();
        });
    </script>
@endpush
