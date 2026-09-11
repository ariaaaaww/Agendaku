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
                    <span class="text-xs text-text-muted font-extrabold uppercase tracking-wider">Total Pengguna</span>
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

            <!-- Tabel Pengaturan Akun Pengguna -->
            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
                <div
                    class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
                    <span>Pengaturan Akun Pengguna</span>
                    <button type="button" onclick="openAddUserModal()"
                        class="btn-primary px-4 py-2.5 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-xs cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-1.5 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">
                        + Tambah Akun Baru
                    </button>
                </div>
                <div class="table-responsive w-full overflow-x-auto rounded-xl border-2 border-border-custom">
                    <table class="admin-table w-full border-collapse text-left text-sm">
                        <thead>
                            <tr class="bg-bg-main font-extrabold text-text-dark uppercase text-xs tracking-wider">
                                <th class="p-4.5 border-b border-border-custom">Nama Lengkap</th>
                                <th class="p-4.5 border-b border-border-custom">Username</th>
                                <th class="p-4.5 border-b border-border-custom">Role / Peran</th>
                                <th class="p-4.5 border-b border-border-custom">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="admin-users-manage-table" class="[&_tr:hover_td]:bg-primary-light"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Akun Pengguna -->
        <div id="modal-add-user"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-6 sm:p-8 shadow-pop-lg max-w-md w-full transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold tracking-tight text-text-dark">Tambah Akun Baru</h3>
                    <button type="button" onclick="closeAddUserModal()"
                        class="text-text-muted hover:text-text-dark text-xl font-bold p-1">✕</button>
                </div>
                <form onsubmit="handleAdminAddUser(event)">
                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Nama
                            Lengkap</label>
                        <input type="text" id="add-user-fullname" placeholder="Contoh: Budi Prasetyo" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="form-group mb-5">
                        <label
                            class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Username</label>
                        <input type="text" id="add-user-username" placeholder="Contoh: budi123" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Role /
                            Peran</label>
                        <select id="add-user-role"
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="osis">Pengurus OSIS</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>

                    <div class="form-group mb-6">
                        <label
                            class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Password</label>
                        <input type="password" id="add-user-password" placeholder="Masukkan password akun" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeAddUserModal()"
                            class="w-1/2 px-4 py-3 border-2 border-text-dark rounded-xl font-extrabold text-sm text-text-dark bg-bg-main hover:bg-border-custom transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="w-1/2 px-4 py-3 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-sm transition-all shadow-pop hover:bg-primary-hover hover:shadow-pop-lg">
                            + Tambah Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Ubah Akun Pengguna -->
        <div id="modal-edit-user"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div
                class="card bg-bg-card border-2 border-text-dark rounded-3xl p-6 sm:p-8 shadow-pop-lg max-w-md w-full transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold tracking-tight text-text-dark">Ubah Akun Pengguna</h3>
                    <button type="button" onclick="closeEditUserModal()"
                        class="text-text-muted hover:text-text-dark text-xl font-bold p-1">✕</button>
                </div>
                <form onsubmit="handleAdminUpdateUser(event)">
                    <input type="hidden" id="edit-user-id">

                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Nama
                            Lengkap</label>
                        <input type="text" id="edit-user-fullname" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="form-group mb-5">
                        <label
                            class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Username</label>
                        <input type="text" id="edit-user-username" required
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="form-group mb-5">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Role /
                            Peran</label>
                        <select id="edit-user-role"
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="osis">Pengurus OSIS</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>

                    <div class="form-group mb-6">
                        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Password
                            Baru (Opsional)</label>
                        <input type="text" id="edit-user-password" placeholder="Kosongkan jika tidak ingin diubah"
                            class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeEditUserModal()"
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
        function renderAdminData() {
            const users = JSON.parse(localStorage.getItem('users')) || [];
            const tasks = JSON.parse(localStorage.getItem('tasks')) || [];

            const siswas = users.filter(u => u.role === 'siswa');
            const completedTasks = tasks.filter(t => t.completed).length;
            const pendingTasks = tasks.filter(t => !t.completed && t.category !== 'acara').length;

            const uStat = document.getElementById('admin-stat-users');
            const cStat = document.getElementById('admin-stat-completed');
            const pStat = document.getElementById('admin-stat-pending');

            if (uStat) uStat.innerText = users.length;
            if (cStat) cStat.innerText = completedTasks;
            if (pStat) pStat.innerText = pendingTasks;

            // 1. Progres Siswa
            const userTable = document.getElementById('admin-user-table-body');
            if (userTable) {
                userTable.innerHTML = '';
                siswas.forEach(siswa => {
                    const uTasks = tasks.filter(t => t.userId === siswa.id);
                    const uDone = uTasks.filter(t => t.completed).length;
                    const uPending = uTasks.filter(t => !t.completed).length;

                    userTable.innerHTML += `
                        <tr class="border-b border-border-custom">
                            <td class="p-4.5 font-extrabold">${siswa.fullname} (@${siswa.username})</td>
                            <td class="p-4.5 font-extrabold text-cat-pribadi">${uDone}</td>
                            <td class="p-4.5 font-extrabold text-prio-tinggi">${uPending}</td>
                            <td class="p-4.5 font-bold"><strong>${uTasks.length}</strong></td>
                        </tr>
                    `;
                });
            }

            // 2. Daftar Acara OSIS & Sekolah
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

            // 3. Pengaturan Akun Pengguna
            const manageTable = document.getElementById('admin-users-manage-table');
            if (manageTable) {
                manageTable.innerHTML = '';
                const roleBadges = {
                    siswa: 'bg-primary-light text-primary-orange border-primary-orange/30',
                    admin: 'bg-amber-100 text-amber-700 border-amber-500/30',
                    guru: 'bg-green-100 text-green-700 border-green-600/30',
                    osis: 'bg-purple-100 text-purple-700 border-purple-600/30',
                };

                users.forEach(u => {
                    manageTable.innerHTML += `
                        <tr class="border-b border-border-custom">
                            <td class="p-4.5 font-extrabold text-text-dark">${u.fullname}</td>
                            <td class="p-4.5 font-semibold text-text-muted">@${u.username}</td>
                            <td class="p-4.5">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[0.7rem] font-extrabold uppercase tracking-wider border ${roleBadges[u.role] || roleBadges.siswa}">
                                    ${u.role}
                                </span>
                            </td>
                            <td class="p-4.5 flex items-center gap-2">
                                <button 
                                    class="bg-amber-100 text-amber-800 border-2 border-amber-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-amber-400 hover:text-text-dark hover:border-text-dark hover:shadow-pop-sm"
                                    onclick="openEditUserModal(${u.id})">
                                    Ubah
                                </button>
                                <button 
                                    class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm"
                                    onclick="deleteUserAccount(${u.id})">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        }

        /* --- HANDLER TAMBAH AKUN --- */
        function openAddUserModal() {
            document.getElementById('modal-add-user').classList.remove('hidden');
        }

        function closeAddUserModal() {
            document.getElementById('modal-add-user').classList.add('hidden');
        }

        function handleAdminAddUser(e) {
            e.preventDefault();
            const fullname = document.getElementById('add-user-fullname').value.trim();
            const username = document.getElementById('add-user-username').value.trim();
            const role = document.getElementById('add-user-role').value;
            const password = document.getElementById('add-user-password').value;

            const users = JSON.parse(localStorage.getItem('users')) || [];
            if (users.some(u => u.username.toLowerCase() === username.toLowerCase())) {
                alert('Username tersebut sudah digunakan oleh akun lain!');
                return;
            }

            const newUser = {
                id: Date.now(),
                fullname,
                username,
                password,
                role
            };
            users.push(newUser);
            localStorage.setItem('users', JSON.stringify(users));

            alert('Akun baru berhasil ditambahkan!');
            e.target.reset();
            closeAddUserModal();
            renderAdminData();
        }

        /* --- HANDLER UBAH AKUN --- */
        function openEditUserModal(userId) {
            const users = JSON.parse(localStorage.getItem('users')) || [];
            const user = users.find(u => u.id == userId);
            if (!user) return;

            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-user-fullname').value = user.fullname;
            document.getElementById('edit-user-username').value = user.username;
            document.getElementById('edit-user-role').value = user.role;
            document.getElementById('edit-user-password').value = '';

            document.getElementById('modal-edit-user').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('modal-edit-user').classList.add('hidden');
        }

        function handleAdminUpdateUser(e) {
            e.preventDefault();
            const id = document.getElementById('edit-user-id').value;
            const fullname = document.getElementById('edit-user-fullname').value.trim();
            const username = document.getElementById('edit-user-username').value.trim();
            const role = document.getElementById('edit-user-role').value;
            const password = document.getElementById('edit-user-password').value;

            let users = JSON.parse(localStorage.getItem('users')) || [];

            // Cek jika username bentrok dengan akun lain
            if (users.some(u => u.id != id && u.username.toLowerCase() === username.toLowerCase())) {
                alert('Username tersebut sudah digunakan oleh akun lain!');
                return;
            }

            users = users.map(u => {
                if (u.id == id) {
                    const updated = {
                        ...u,
                        fullname,
                        username,
                        role,
                    };
                    if (password && password.trim() !== '') {
                        updated.password = password;
                    }
                    return updated;
                }
                return u;
            });

            localStorage.setItem('users', JSON.stringify(users));

            // Jika akun yang diubah adalah akun admin yang sedang login, perbarui session
            if (currentUser && currentUser.id == id) {
                const me = users.find(u => u.id == id);
                currentUser = me;
                sessionStorage.setItem('currentUser', JSON.stringify(me));
                updateNavHeader();
            }

            alert('Data akun berhasil diperbarui!');
            closeEditUserModal();
            renderAdminData();
        }

        /* --- HANDLER HAPUS AKUN --- */
        function deleteUserAccount(userId) {
            if (currentUser && currentUser.id == userId) {
                alert('Anda tidak dapat menghapus akun yang sedang Anda gunakan saat ini!');
                return;
            }

            if (confirm('Apakah Anda yakin ingin menghapus akun ini? Akun yang dihapus tidak dapat dipulihkan.')) {
                let users = JSON.parse(localStorage.getItem('users')) || [];
                users = users.filter(u => u.id != userId);
                localStorage.setItem('users', JSON.stringify(users));

                alert('Akun berhasil dihapus!');
                renderAdminData();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderAdminData();
        });
    </script>
@endpush
