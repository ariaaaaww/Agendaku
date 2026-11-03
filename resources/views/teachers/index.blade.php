@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Panel Guru')

@section('content')
    <section id="page-dashboard">
        <!-- Tab Kalender, To-Do List, Acara Sekolah, Tambah Agenda -->
        @include('components.dashboard-views')

        <!-- TAB 5: PANEL GURU -->
        <div id="view-role-panel" class="hidden">
            <h2 class="text-2xl font-extrabold mb-6 tracking-tight">Panel Pengelolaan Guru</h2>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Buat Pengumuman Sekolah</div>
                <form onsubmit="handlePostAnnouncement(event)">
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Pesan
                            Pengumuman</label>
                        <textarea id="announcement-input" rows="3" placeholder="Tuliskan pengumuman untuk seluruh siswa..." required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px"></textarea>
                    </div>
                    <button type="submit"
                        class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">Kirim
                        Pengumuman</button>
                </form>
            </div>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Tambah Tugas/Acara Massal ke Siswa & Kalender</div>
                <form onsubmit="handleGuruAddTask(event)">
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Judul Tugas
                            /
                            Acara</label>
                        <input type="text" id="guru-task-title" placeholder="Contoh: Ujian Tengah Semester IPA" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tipe
                            Agenda</label>
                        <select id="guru-task-type"
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                            <option value="sekolah">Tugas Akademik</option>
                            <option value="acara">Acara / Ujian Sekolah</option>
                        </select>
                    </div>
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tanggal /
                            Deadline</label>
                        <input type="date" id="guru-task-date" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>
                    <button type="submit"
                        class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">+
                        Publikasikan ke Seluruh Sistem</button>
                </form>
            </div>

            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    Kelola Tugas & Acara Sistem</div>
                <div class="table-responsive w-full overflow-x-auto rounded-xl border-2 border-border-custom">
                    <table class="admin-table w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-bg-main font-extrabold text-text-dark uppercase text-xs tracking-wider">
                                <th class="p-4.5 border-b border-border-custom">Judul</th>
                                <th class="p-4.5 border-b border-border-custom">Kategori</th>
                                <th class="p-4.5 border-b border-border-custom">Tanggal</th>
                                <th class="p-4.5 border-b border-border-custom">Pembuat</th>
                                <th class="p-4.5 border-b border-border-custom">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="guru-all-tasks-table" class="[&_tr:hover_td]:bg-primary-light">
                            @if (isset($allTasks) && $allTasks->count() > 0)
                                @foreach ($allTasks as $t)
                                    <tr id="guru-task-row-{{ $t->id }}" class="border-b border-border-custom">
                                        <td class="p-4.5 font-extrabold">{{ $t->title }}</td>
                                        <td class="p-4.5 font-bold uppercase">{{ $t->category }}</td>
                                        <td class="p-4.5">{{ \Carbon\Carbon::parse($t->deadline)->format('Y-m-d') }}</td>
                                        <td class="p-4.5">{{ $t->creator->name ?? ($t->user->name ?? 'System') }}</td>
                                        <td class="p-4.5 flex items-center gap-2">
                                            <button
                                                class="bg-amber-100 text-amber-800 border-2 border-amber-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-amber-400 hover:text-text-dark hover:border-text-dark hover:shadow-pop-sm"
                                                onclick="openEditGuruModal({{ $t->id }}, '{{ addslashes($t->title) }}', '{{ $t->category }}', '{{ \Carbon\Carbon::parse($t->deadline)->format('Y-m-d') }}')">
                                                Ubah
                                            </button>
                                            <button
                                                class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm"
                                                onclick="deleteTask({{ $t->id }})">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-text-muted font-bold">Belum ada tugas atau acara sistem.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Tugas & Acara Guru -->
        <div id="modal-edit-guru"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-6 sm:p-8 shadow-pop-lg max-w-md w-full transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold tracking-tight text-text-dark">Ubah Tugas & Acara</h3>
                    <button type="button" onclick="closeEditGuruModal()"
                        class="text-text-muted hover:text-text-dark text-xl font-bold p-1">✕</button>
                </div>

                <form onsubmit="handleGuruUpdateTask(event)">
                    <!-- ID tugas/acara yang diedit -->
                    <input type="hidden" id="edit-guru-task-id">

                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Judul Tugas
                            / Acara</label>
                        <input type="text" id="edit-guru-task-title" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tipe
                            Agenda</label>
                        <select id="edit-guru-task-type"
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                            <option value="sekolah">Tugas Akademik</option>
                            <option value="acara">Acara / Ujian Sekolah</option>
                        </select>
                    </div>

                    <div class="form-group mb-6">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tanggal /
                            Deadline</label>
                        <input type="date" id="edit-guru-task-date" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeEditGuruModal()"
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
        async function handlePostAnnouncement(e) {
            e.preventDefault();
            const txt = document.getElementById('announcement-input').value;
            try {
                const res = await fetch("{{ route('announcements.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ content: txt })
                });

                if (res.ok) {
                    alert('Pengumuman telah diperbarui!');
                    window.location.reload();
                } else {
                    const err = await res.json();
                    alert(err.message || 'Gagal memperbarui pengumuman.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            }
        }

        async function handleGuruAddTask(e) {
            e.preventDefault();
            const title = document.getElementById('guru-task-title').value;
            const category = document.getElementById('guru-task-type').value;
            const deadline = document.getElementById('guru-task-date').value;

            try {
                const res = await fetch("{{ route('teachers.tasks.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ title, category, deadline })
                });

                if (res.ok) {
                    alert('Tugas / Acara berhasil ditambahkan ke seluruh siswa & kalender!');
                    window.location.reload();
                } else {
                    const err = await res.json();
                    alert(err.message || 'Gagal menerbitkan tugas/acara.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            }
        }

        function openEditGuruModal(taskId, title = '', category = 'sekolah', deadline = '') {
            document.getElementById('edit-guru-task-id').value = taskId;
            document.getElementById('edit-guru-task-title').value = title;
            document.getElementById('edit-guru-task-type').value = category;
            document.getElementById('edit-guru-task-date').value = deadline;

            document.getElementById('modal-edit-guru').classList.remove('hidden');
        }

        function closeEditGuruModal() {
            document.getElementById('modal-edit-guru').classList.add('hidden');
        }

        async function handleGuruUpdateTask(e) {
            e.preventDefault();

            const id = document.getElementById('edit-guru-task-id').value;
            const title = document.getElementById('edit-guru-task-title').value.trim();
            const category = document.getElementById('edit-guru-task-type').value;
            const deadline = document.getElementById('edit-guru-task-date').value;

            try {
                const res = await fetch(`/tasks/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ title, category, deadline })
                });

                if (res.ok) {
                    alert('Tugas / Acara berhasil diperbarui!');
                    window.location.reload();
                } else {
                    const err = await res.json();
                    alert(err.message || 'Gagal memperbarui data.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            }
        }
    </script>
@endpush
