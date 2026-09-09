@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Panel OSIS')

@section('content')
    <section id="page-dashboard">
        <!-- Tab Kalender, To-Do List, Acara Sekolah, Tambah Agenda -->
        @include('components.dashboard-views')

        <!-- TAB 5: PANEL OSIS -->
        <div id="view-role-panel" class="hidden">
            <h2 class="text-2xl font-extrabold mb-6 tracking-tight">Panel Pengurus OSIS</h2>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Tambah Acara OSIS Baru</div>
                <form onsubmit="handleOsisAddEvent(event)">
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Nama Acara
                            OSIS</label>
                        <input type="text" id="osis-event-title" placeholder="Misal: Turnamen Classmeet / Pensi" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tanggal
                            Pelaksanaan</label>
                        <input type="date" id="osis-event-date" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>
                    <button type="submit"
                        class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">+
                        Terbitkan Acara ke Kalender</button>
                </form>
            </div>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Daftar Acara OSIS Yang Terdaftar</div>
                <div class="table-responsive w-full overflow-x-auto rounded-xl border-2 border-border-custom">
                    <table class="admin-table w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-bg-main font-extrabold text-text-dark uppercase text-xs tracking-wider">
                                <th class="p-4.5 border-b border-border-custom">Judul Acara</th>
                                <th class="p-4.5 border-b border-border-custom">Tanggal Pelaksanaan</th>
                                <th class="p-4.5 border-b border-border-custom">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="osis-events-table" class="[&_tr:hover_td]:bg-primary-light"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Acara OSIS -->
        <div id="modal-edit-osis"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-6 sm:p-8 shadow-pop-lg max-w-md w-full transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold tracking-tight text-text-dark">Ubah Acara OSIS</h3>
                    <button type="button" onclick="closeEditModal()"
                        class="text-text-muted hover:text-text-dark text-xl font-bold p-1">✕</button>
                </div>

                <form onsubmit="handleOsisUpdateEvent(event)">
                    <!-- Menyimpan ID acara yang sedang diedit -->
                    <input type="hidden" id="edit-event-id">

                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Nama Acara
                            OSIS</label>
                        <input type="text" id="edit-event-title" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="form-group mb-6">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tanggal
                            Pelaksanaan</label>
                        <input type="date" id="edit-event-date" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeEditModal()"
                            class="w-1/2 px-4 py-3 border-2 border-text-dark rounded-xl font-extrabold text-sm text-text-dark bg-bg-main hover:bg-border-custom transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-1/2 px-4 py-3 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-sm transition-all shadow-pop hover:bg-primary-hover hover:shadow-pop-lg">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function handleOsisAddEvent(e) {
            e.preventDefault();
            const title = document.getElementById('osis-event-title').value;
            const deadline = document.getElementById('osis-event-date').value;

            const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
            tasks.push({
                id: Date.now(),
                userId: 0,
                title,
                category: 'acara',
                deadline,
                priority: 'sedang',
                completed: false,
                createdBy: 'Pengurus OSIS'
            });

            localStorage.setItem('tasks', JSON.stringify(tasks));
            alert('Acara OSIS berhasil dipublikasikan!');
            e.target.reset();
            renderOsisData();
            if (typeof renderDashboard === 'function') renderDashboard();
        }

        function renderOsisData() {
            const tasks = (JSON.parse(localStorage.getItem('tasks')) || []).filter(t => t.category === 'acara');
            const table = document.getElementById('osis-events-table');
            if (!table) return;
            table.innerHTML = '';

            tasks.forEach(ev => {
                table.innerHTML += `
                    <tr class="border-b border-border-custom">
                        <td class="p-4.5 font-extrabold">${ev.title}</td>
                        <td class="p-4.5">${ev.deadline}</td>
                        <td class="p-4.5 flex items-center gap-2">
                            <button 
                                class="bg-amber-100 text-amber-800 border-2 border-amber-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-amber-400 hover:text-text-dark hover:border-text-dark hover:shadow-pop-sm" 
                                onclick="openEditModal(${ev.id})">
                                Ubah
                            </button>
                            <button 
                                class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm" 
                                onclick="deleteTask(${ev.id})">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        // 1. Ambil data lama dari localStorage lalu tampilkan di modal
        function openEditModal(eventId) {
            const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
            const eventToEdit = tasks.find(t => t.id === eventId);
            if (!eventToEdit) return;

            document.getElementById('edit-event-id').value = eventToEdit.id;
            document.getElementById('edit-event-title').value = eventToEdit.title;
            document.getElementById('edit-event-date').value = eventToEdit.deadline;

            document.getElementById('modal-edit-osis').classList.remove('hidden');
        }

        // 2. Tutup modal jika batal / selesai
        function closeEditModal() {
            document.getElementById('modal-edit-osis').classList.add('hidden');
        }

        // 3. Simpan nilai baru ke localStorage saat form modal disubmit
        function handleOsisUpdateEvent(e) {
            e.preventDefault();

            const id = Number(document.getElementById('edit-event-id').value);
            const newTitle = document.getElementById('edit-event-title').value.trim();
            const newDate = document.getElementById('edit-event-date').value;

            let tasks = JSON.parse(localStorage.getItem('tasks')) || [];

            tasks = tasks.map(task => {
                if (task.id === id) {
                    return {
                        ...task,
                        title: newTitle,
                        deadline: newDate
                    };
                }
                return task;
            });

            localStorage.setItem('tasks', JSON.stringify(tasks));

            alert('Acara OSIS berhasil diperbarui!');
            closeEditModal();
            renderOsisData();
            if (typeof renderDashboard === 'function') renderDashboard();
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderOsisData();
        });
    </script>
@endpush
