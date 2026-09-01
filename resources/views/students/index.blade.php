@extends('layouts.app')

@section('title', $title ?? 'Agendaku - Dashboard Siswa')

@section('content')
<section id="page-dashboard">

  <!-- Announcement Banner -->
  <div id="announcement-banner" class="announcement-card hidden bg-gradient-to-br from-sky-100 to-sky-200 border-2 border-text-dark rounded-2xl p-6 mb-8 shadow-pop-5px relative">
    <h4 class="text-sky-700 font-extrabold text-lg mb-1.5 flex items-center gap-2">📢 Pengumuman Guru / Sekolah</h4>
    <p id="announcement-text" class="text-sky-950 text-sm font-semibold leading-relaxed">-</p>
  </div>

  <!-- Alert Banner -->
  <div id="alert-banner-container" class="alert-banner bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-text-dark rounded-2xl p-6 mb-8 flex items-center gap-5 shadow-pop-5px">
    <div class="alert-icon bg-primary-orange text-white w-11 h-11 rounded-full grid place-items-center font-extrabold text-xl shrink-0 border-2 border-text-dark shadow-pop-sm">!</div>
    <div>
      <h4 class="text-base font-extrabold text-orange-800">Pengingat Deadline Esok Hari</h4>
      <p id="alert-tomorrow-text" class="text-sm text-orange-700 font-semibold">Memuat pengingat...</p>
    </div>
  </div>

  <!-- TAB 1: KALENDER -->
  <div id="view-kalender" class="card calendar-container bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200 w-full">
    <div class="calendar-header flex justify-between items-center mb-7 flex-wrap gap-3">
      <h3 class="text-2xl font-extrabold" id="cal-month-year">Kalender</h3>
      <div class="calendar-legend flex gap-4 text-xs font-bold">
        <span class="text-cat-sekolah">● Sekolah</span>
        <span class="text-cat-pribadi">● Pribadi</span>
        <span class="text-cat-acara">● Acara OSIS</span>
      </div>
    </div>
    <div class="calendar-grid grid grid-cols-7 gap-2.5 text-center">
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Minggu</div>
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Senin</div>
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Selasa</div>
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Rabu</div>
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Kamis</div>
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Jumat</div>
      <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Sabtu</div>
    </div>
    <div id="calendar-cells" class="calendar-grid grid grid-cols-7 gap-2.5 text-center">
    </div>
  </div>

  <!-- TAB 2: TO-DO LIST -->
  <div id="view-tugas" class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
      Daftar Tugas Belum Selesai
      <span id="pending-count" class="bg-primary-light text-primary-orange px-3.5 py-1 rounded-full text-xs border border-primary-orange">0</span>
    </div>
    <div id="pending-tasks-list" class="task-list flex flex-col gap-4">
    </div>
  </div>

  <!-- TAB 3: ACARA SEKOLAH -->
  <div id="view-acara" class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Acara & Agenda Kegiatan Sekolah / OSIS</div>
    <div id="school-events-list" class="task-list flex flex-col gap-4">
    </div>
  </div>

  <!-- TAB 4: TAMBAH AGENDA -->
  <div id="view-tambah" class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200 max-w-[650px] mx-auto">
    <div class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">Tambah Tugas / Kegiatan Baru</div>
    <form onsubmit="handleAddTask(event)">
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Judul Agenda / Tugas</label>
        <input type="text" id="task-title" placeholder="Misal: Tugas Matematika / Latihan Futsal" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Kategori</label>
        <select id="task-category" class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
          <option value="sekolah">Sekolah / Akademis</option>
          <option value="pribadi">Kegiatan Pribadi</option>
        </select>
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tenggat Waktu / Tanggal</label>
        <input type="date" id="task-deadline" required class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
      </div>
      <div class="form-group mb-5">
        <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tingkat Prioritas</label>
        <select id="task-priority" class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
          <option value="tinggi">Tinggi</option>
          <option value="sedang" selected>Sedang</option>
          <option value="rendah">Rendah</option>
        </select>
      </div>
      <button type="submit" class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">+ Masukkan Ke Kalender</button>
    </form>
  </div>

</section>
@endsection

@push('scripts')
<script>
  function handleAddTask(e) {
    e.preventDefault();
    const title = document.getElementById('task-title').value;
    const category = document.getElementById('task-category').value;
    const deadline = document.getElementById('task-deadline').value;
    const priority = document.getElementById('task-priority').value;

    const tasks = JSON.parse(localStorage.getItem('tasks'));
    const newTask = {
      id: Date.now(),
      userId: currentUser ? currentUser.id : 1,
      title,
      category,
      deadline,
      priority,
      completed: false,
      createdBy: currentUser ? currentUser.fullname : 'Siswa'
    };

    tasks.push(newTask);
    localStorage.setItem('tasks', JSON.stringify(tasks));

    e.target.reset();
    renderDashboard();
    switchTab('kalender');
  }

  function renderDashboard() {
    const allTasks = JSON.parse(localStorage.getItem('tasks')) || [];

    // Announcement
    const announcement = localStorage.getItem('announcement');
    const annBox = document.getElementById('announcement-banner');
    if (annBox) {
      if (announcement) {
        annBox.classList.remove('hidden');
        document.getElementById('announcement-text').innerText = announcement;
      } else {
        annBox.classList.add('hidden');
      }
    }

    // Filter Tasks by Role
    let userTasks;
    if (currentUser && (currentUser.role === 'admin' || currentUser.role === 'guru' || currentUser.role === 'osis')) {
      userTasks = allTasks;
    } else {
      const currentUserId = currentUser ? currentUser.id : 1;
      userTasks = allTasks.filter(t => t.userId === currentUserId || t.category === 'acara' || t.userId === 0);
    }

    // Alert Tomorrow
    const tomorrowStr = getTomorrowDateString();
    const tomorrowTasks = userTasks.filter(t => t.deadline === tomorrowStr && !t.completed);
    const alertEl = document.getElementById('alert-tomorrow-text');
    if (alertEl) {
      if (tomorrowTasks.length > 0) {
        alertEl.innerText = `Kamu memiliki ${tomorrowTasks.length} agenda/tugas untuk esok hari: "${tomorrowTasks[0].title}"${tomorrowTasks.length > 1 ? ' dan lainnya.' : '.'}`;
      } else {
        alertEl.innerText = "Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!";
      }
    }

    // Pending Tasks List
    const pendingTasks = userTasks.filter(t => !t.completed && t.category !== 'acara');
    const pendingCountEl = document.getElementById('pending-count');
    if (pendingCountEl) pendingCountEl.innerText = pendingTasks.length;

    const pendingListEl = document.getElementById('pending-tasks-list');
    if (pendingListEl) {
      pendingListEl.innerHTML = '';
      const borderCategory = {
        sekolah: 'border-l-cat-sekolah',
        pribadi: 'border-l-cat-pribadi',
        acara: 'border-l-cat-acara'
      };

      const tagPriority = {
        tinggi: 'bg-red-100 text-prio-tinggi border-red-300',
        sedang: 'bg-amber-100 text-prio-sedang border-amber-200',
        rendah: 'bg-emerald-100 text-prio-rendah border-emerald-300'
      };

      if (pendingTasks.length === 0) {
        pendingListEl.innerHTML = `<p class="text-center text-text-muted p-10 font-semibold">Semua tugas telah selesai dikerjakan! 🎉</p>`;
      } else {
        pendingTasks.forEach(task => {
          pendingListEl.innerHTML += `
            <div class="task-item border-l-6 ${borderCategory[task.category] || 'border-l-primary-orange'} p-5 rounded-2xl bg-bg-main border-2 border-border-custom flex justify-between items-center gap-4 transition-all duration-200 hover:border-text-dark hover:bg-white hover:shadow-pop hover:-translate-y-0.5">
              <div class="task-info">
                <h4 class="text-lg font-extrabold mb-1 text-text-dark">${task.title}</h4>
                <p class="text-sm text-text-muted font-semibold">Tenggat Waktu: <strong class="text-text-dark">${task.deadline}</strong></p>
                <div class="task-tags flex gap-2 mt-3 flex-wrap">
                  <span class="tag text-[0.72rem] px-3 py-1 rounded-full font-extrabold tracking-wider border ${tagPriority[task.priority]}">PRIORITAS ${task.priority.toUpperCase()}</span>
                  <span class="tag text-[0.72rem] px-3 py-1 rounded-full font-extrabold tracking-wider border bg-border-custom text-text-dark border-transparent">${task.category.toUpperCase()}</span>
                </div>
              </div>
              <div class="task-actions flex gap-2.5 items-center shrink-0">
                <button class="btn-action border-2 border-text-dark rounded-full w-10 h-10 cursor-pointer grid place-items-center font-extrabold text-base transition-all duration-200 shadow-pop-sm bg-white text-text-dark hover:bg-cat-pribadi hover:text-white hover:scale-110" onclick="toggleTaskComplete(${task.id})" title="Tandai Selesai">✓</button>
                <button class="btn-action border-2 border-text-dark rounded-full w-10 h-10 cursor-pointer grid place-items-center font-extrabold text-base transition-all duration-200 shadow-pop-sm bg-white text-text-dark hover:bg-red-500 hover:text-white hover:scale-110" onclick="deleteTask(${task.id})" title="Hapus Tugas">🗑️</button>
              </div>
            </div>
          `;
        });
      }
    }

    // School Events List
    const eventsList = allTasks.filter(t => t.category === 'acara');
    const eventsListEl = document.getElementById('school-events-list');
    if (eventsListEl) {
      eventsListEl.innerHTML = '';
      if (eventsList.length === 0) {
        eventsListEl.innerHTML = `<p class="text-center text-text-muted p-10 font-semibold">Belum ada acara sekolah yang diselenggarakan.</p>`;
      } else {
        eventsList.forEach(event => {
          eventsListEl.innerHTML += `
            <div class="task-item border-l-6 border-l-cat-acara p-5 rounded-2xl bg-bg-main border-2 border-border-custom flex justify-between items-center gap-4 transition-all duration-200 hover:border-text-dark hover:bg-white hover:shadow-pop hover:-translate-y-0.5">
              <div class="task-info">
                <h4 class="text-lg font-extrabold mb-1 text-text-dark">${event.title}</h4>
                <p class="text-sm text-text-muted font-semibold">Tanggal Pelaksanaan: <strong class="text-text-dark">${event.deadline}</strong></p>
                <div class="task-tags flex gap-2 mt-3 flex-wrap">
                  <span class="tag text-[0.72rem] px-3 py-1 rounded-full font-extrabold tracking-wider border bg-cat-acara-bg text-cat-acara border-purple-200">ACARA SEKOLAH / OSIS</span>
                </div>
              </div>
            </div>
          `;
        });
      }
    }

    // Render Calendar
    renderCalendar(userTasks);
  }

  function renderCalendar(userTasks) {
    const calCellsEl = document.getElementById('calendar-cells');
    if (!calCellsEl) return;
    calCellsEl.innerHTML = '';

    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const monthYearEl = document.getElementById('cal-month-year');
    if (monthYearEl) monthYearEl.innerText = `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
      calCellsEl.innerHTML += `<div class="cal-cell bg-transparent border-none shadow-none pointer-events-none"></div>`;
    }

    const pillPills = {
      sekolah: 'bg-cat-sekolah-bg text-cat-sekolah border-blue-200',
      pribadi: 'bg-cat-pribadi-bg text-cat-pribadi border-emerald-200',
      acara: 'bg-cat-acara-bg text-cat-acara border-purple-200'
    };

    for (let day = 1; day <= daysInMonth; day++) {
      const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const isToday = dateStr === getTodayDateString();

      const dayTasks = userTasks.filter(t => t.deadline === dateStr && !t.completed);

      let tasksHtml = '<div class="cal-task-list w-full flex flex-col gap-1 overflow-y-auto max-h-[65px]">';
      dayTasks.forEach(t => {
        tasksHtml += `<div class="cal-task-pill text-[0.6rem] lg:text-[0.7rem] px-1.5 py-0.5 lg:px-1.5 lg:py-1 rounded-md font-bold text-left whitespace-nowrap overflow-hidden text-ellipsis w-full border ${pillPills[t.category] || pillPills.sekolah}" title="${t.title}">${t.title}</div>`;
      });
      tasksHtml += '</div>';

      const todayClasses = isToday ? 'border-primary-orange bg-white shadow-[0_0_0_3px_#FFF0ED]' : 'border-border-custom bg-bg-main hover:border-text-dark hover:bg-white hover:-translate-y-0.5 hover:shadow-pop-3px';

      calCellsEl.innerHTML += `
        <div class="cal-cell min-h-[70px] lg:min-h-[105px] rounded-xl p-1 lg:p-2 text-sm lg:text-base font-bold flex flex-col items-start relative border-2 transition-all duration-200 ${todayClasses}">
          <span class="cal-day-number text-xs font-extrabold text-text-dark mb-1.5 ${isToday ? 'bg-primary-orange text-white w-6 h-6 rounded-full grid place-items-center font-extrabold' : ''}">${day}</span>
          ${dayTasks.length > 0 ? tasksHtml : ''}
        </div>
      `;
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    renderDashboard();
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
      switchTab(tabParam);
    } else {
      switchTab('kalender');
    }
  });
</script>
@endpush