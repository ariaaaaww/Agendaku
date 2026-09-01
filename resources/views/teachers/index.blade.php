@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Panel Guru')

@section('content')
<div id="panel-guru">
  <h2 class="text-2xl font-extrabold mb-6 tracking-tight">Panel Pengelolaan Guru</h2>

  <div class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Buat Pengumuman Sekolah</div>
    <form onsubmit="handlePostAnnouncement(event)">
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Pesan Pengumuman</label>
        <textarea id="announcement-input" rows="3" placeholder="Tuliskan pengumuman untuk seluruh siswa..." required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px"></textarea>
      </div>
      <button type="submit" class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">Kirim Pengumuman</button>
    </form>
  </div>

  <div class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Tambah Tugas/Acara Massal ke Siswa & Kalender</div>
    <form onsubmit="handleGuruAddTask(event)">
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Judul Tugas / Acara</label>
        <input type="text" id="guru-task-title" placeholder="Contoh: Ujian Tengah Semester IPA" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tipe Agenda</label>
        <select id="guru-task-type" class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
          <option value="sekolah">Tugas Akademik</option>
          <option value="acara">Acara / Ujian Sekolah</option>
        </select>
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tanggal / Deadline</label>
        <input type="date" id="guru-task-date" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <button type="submit" class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">+ Publikasikan ke Seluruh Sistem</button>
    </form>
  </div>

  <div class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Kelola Tugas & Acara Sistem</div>
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
        <tbody id="guru-all-tasks-table" class="[&_tr:hover_td]:bg-primary-light"></tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function handlePostAnnouncement(e) {
    e.preventDefault();
    const txt = document.getElementById('announcement-input').value;
    localStorage.setItem('announcement', txt);
    alert('Pengumuman telah diperbarui!');
    e.target.reset();
  }

  function handleGuruAddTask(e) {
    e.preventDefault();
    const title = document.getElementById('guru-task-title').value;
    const category = document.getElementById('guru-task-type').value;
    const deadline = document.getElementById('guru-task-date').value;

    const users = JSON.parse(localStorage.getItem('users')) || [];
    const tasks = JSON.parse(localStorage.getItem('tasks')) || [];

    if (category === 'acara') {
      tasks.push({
        id: Date.now(),
        userId: 0,
        title,
        category: 'acara',
        deadline,
        priority: 'tinggi',
        completed: false,
        createdBy: currentUser ? currentUser.fullname : 'Guru'
      });
    } else {
      const siswas = users.filter(u => u.role === 'siswa');
      siswas.forEach(s => {
        tasks.push({
          id: Date.now() + Math.random(),
          userId: s.id,
          title: `${title} (Tugas Guru)`,
          category: 'sekolah',
          deadline,
          priority: 'tinggi',
          completed: false,
          createdBy: currentUser ? currentUser.fullname : 'Guru'
        });
      });
    }

    localStorage.setItem('tasks', JSON.stringify(tasks));
    alert('Tugas / Acara berhasil ditambahkan ke seluruh siswa & kalender!');
    e.target.reset();
    renderGuruData();
  }

  function renderGuruData() {
    const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    const table = document.getElementById('guru-all-tasks-table');
    if (!table) return;
    table.innerHTML = '';

    tasks.forEach(t => {
      table.innerHTML += `
        <tr class="border-b border-border-custom">
          <td class="p-4.5 font-extrabold">${t.title}</td>
          <td class="p-4.5 font-bold uppercase">${t.category}</td>
          <td class="p-4.5">${t.deadline}</td>
          <td class="p-4.5">${t.createdBy || 'System'}</td>
          <td class="p-4.5"><button class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm" onclick="deleteTask(${t.id})">Hapus</button></td>
        </tr>
      `;
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    renderGuruData();
  });
</script>
@endpush
