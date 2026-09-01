@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Panel OSIS')

@section('content')
<div id="panel-osis">
  <h2 class="text-2xl font-extrabold mb-6 tracking-tight">Panel Pengurus OSIS</h2>

  <div class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Tambah Acara OSIS Baru</div>
    <form onsubmit="handleOsisAddEvent(event)">
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Nama Acara OSIS</label>
        <input type="text" id="osis-event-title" placeholder="Misal: Turnamen Classmeet / Pensi" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tanggal Pelaksanaan</label>
        <input type="date" id="osis-event-date" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <button type="submit" class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">+ Terbitkan Acara ke Kalender</button>
    </form>
  </div>

  <div class="card bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Daftar Acara OSIS Yang Terdaftar</div>
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
          <td class="p-4.5"><button class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm" onclick="deleteTask(${ev.id})">Hapus</button></td>
        </tr>
      `;
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    renderOsisData();
  });
</script>
@endpush
