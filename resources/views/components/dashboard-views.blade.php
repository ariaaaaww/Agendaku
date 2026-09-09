<!-- Announcement Banner -->
<div id="announcement-banner"
    class="announcement-card hidden bg-[#E3F2FD] border-2 border-black rounded-[2rem] px-6 py-4 mb-8 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] relative flex items-center gap-3">
    <!-- Icon Container -->
    <div class="alert-icon bg-[#2B6B9E] text-white w-10 h-10 rounded-full grid place-items-center shrink-0">
        <span class="icon-[garden--megaphone-stroke-12] text-lg"></span>
    </div>
    <!-- Text Container -->
    <div class="flex flex-col justify-center">
        <h4 class="text-black font-extrabold text-base leading-snug">
            Pengumuman Guru / Sekolah
        </h4>
        <p id="announcement-text" class="text-gray-800 text-sm font-medium leading-normal mt-0.5">
            -
        </p>
    </div>
</div>

<!-- Alert Banner -->
<div id="alert-banner-container"
    class="alert-banner bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-text-dark rounded-[2rem] px-6 py-4 mb-8 flex items-center gap-2 shadow-pop-5px">
    <div
        class="alert-icon bg-primary-orange text-white w-11 h-11 rounded-full grid place-items-center font-extrabold text-xl shrink-0 border-2">
        !</div>
    <div>
        <h4 class="text-base font-extrabold text-orange-800">Pengingat Deadline Esok Hari</h4>
        <p id="alert-tomorrow-text" class="text-sm text-orange-700 font-semibold">Memuat pengingat...</p>
    </div>
</div>

<!-- TAB 1: KALENDER -->
<div id="view-kalender"
    class="card calendar-container bg-bg-card border-2 border-text-dark rounded-[2rem] p-8 shadow-pop-lg mb-8 transition-all duration-200 w-full">
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
<div id="view-tugas"
    class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div
        class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
        Daftar Tugas Belum Selesai
        <span id="pending-count"
            class="bg-primary-light text-primary-orange px-3.5 py-1 rounded-full text-xs border border-primary-orange">0</span>
    </div>
    <div id="pending-tasks-list" class="task-list flex flex-col gap-4">
    </div>
</div>

<!-- TAB 3: ACARA SEKOLAH -->
<div id="view-acara"
    class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div
        class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
        Acara & Agenda Kegiatan Sekolah / OSIS</div>
    <div id="school-events-list" class="task-list flex flex-col gap-4">
    </div>
</div>

<!-- TAB 4: TAMBAH AGENDA -->
<div id="view-tambah"
    class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200 max-w-[650px] mx-auto">
    <div
        class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
        Tambah Tugas / Kegiatan Baru</div>
    <form onsubmit="handleAddTask(event)">
        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Judul Agenda /
                Tugas</label>
            <input type="text" id="task-title" placeholder="Misal: Tugas Matematika / Latihan Futsal" required
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
        </div>
        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Kategori</label>
            <select id="task-category"
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                <option value="sekolah">Sekolah / Akademis</option>
                <option value="pribadi">Kegiatan Pribadi</option>
            </select>
        </div>
        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tenggat Waktu /
                Tanggal</label>
            <input type="date" id="task-deadline" required
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
        </div>
        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tingkat
                Prioritas</label>
            <select id="task-priority"
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                <option value="tinggi">Tinggi</option>
                <option value="sedang" selected>Sedang</option>
                <option value="rendah">Rendah</option>
            </select>
        </div>
        <button type="submit"
            class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">+
            Masukkan Ke Kalender</button>
    </form>
</div>
