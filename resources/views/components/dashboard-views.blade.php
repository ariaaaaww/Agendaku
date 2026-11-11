<!-- Announcement Banner -->
@if (isset($announcement) && $announcement)
    <div id="announcement-banner"
        class="announcement-card bg-[#E3F2FD] border-2 border-black rounded-[2rem] px-6 py-4 mb-8 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] relative flex items-center gap-3">
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
                {{ $announcement->content }}
            </p>
        </div>
    </div>
@endif

<!-- Alert Banner -->
@php
    $alert = $alertData ?? [
        'type' => 'empty',
        'count' => 0,
        'message' => 'Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!',
    ];
    $theme = match ($alert['type']) {
        'overdue' => [
            'bg' => 'from-red-50 to-red-100 border-red-500',
            'badge' => 'bg-red-500 text-white',
            'title' => 'text-red-900',
            'desc' => 'text-red-800',
            'header' => 'Tugas Melewati Batas Waktu!',
            'icon' => '⚠️',
        ],
        'today' => [
            'bg' => 'from-amber-50 to-amber-100 border-amber-500',
            'badge' => 'bg-amber-500 text-white',
            'title' => 'text-amber-900',
            'desc' => 'text-amber-800',
            'header' => 'Tenggat Waktu Hari Ini',
            'icon' => '⏰',
        ],
        'tomorrow' => [
            'bg' => 'from-orange-50 to-orange-100 border-text-dark',
            'badge' => 'bg-primary-orange text-white',
            'title' => 'text-orange-900',
            'desc' => 'text-orange-800',
            'header' => 'Pengingat Deadline Esok Hari',
            'icon' => '!',
        ],
        default => [
            'bg' => 'from-emerald-50 to-emerald-100 border-emerald-600',
            'badge' => 'bg-emerald-600 text-white',
            'title' => 'text-emerald-900',
            'desc' => 'text-emerald-800',
            'header' => 'Semua Beres!',
            'icon' => '✓',
        ],
    };
@endphp
<div id="alert-banner-container"
    class="alert-banner bg-gradient-to-br {{ $theme['bg'] }} border-2 rounded-[2rem] px-6 py-4 mb-8 flex items-center gap-3 shadow-pop-5px transition-all">
    <div
        class="alert-icon {{ $theme['badge'] }} w-11 h-11 rounded-full grid place-items-center font-extrabold text-xl shrink-0 border-2 border-text-dark">
        {{ $theme['icon'] }}
    </div>
    <div class="flex-1">
        <h4 class="text-base font-extrabold {{ $theme['title'] }}">{{ $theme['header'] }}</h4>
        <p id="alert-tomorrow-text" class="text-sm {{ $theme['desc'] }} font-semibold">{{ $alert['message'] }}</p>
    </div>
    @if (($alert['count'] ?? 0) > 0)
        <span
            class="px-3 py-1 bg-white border-2 border-text-dark text-text-dark text-xs font-extrabold rounded-full shadow-pop-sm">
            {{ $alert['count'] }} Agenda
        </span>
    @endif
</div>

<!-- TAB 1: KALENDER -->
@php
    // Nilai default jika controller tidak mengirim data kalender
    $calDate = $calDate ?? now()->startOfMonth();
    $daysInMonth = $daysInMonth ?? $calDate->daysInMonth;
    $firstDayOfWeek = $firstDayOfWeek ?? $calDate->dayOfWeek;
    $calAgendas = $calAgendas ?? collect();
@endphp

<div id="view-kalender"
    class="card calendar-container bg-bg-card border-2 border-text-dark rounded-[2rem] p-8 shadow-pop-lg mb-8 transition-all duration-200 w-full">
    <div class="calendar-header flex justify-between items-center mb-7 flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <!-- Judul Bulan & Tahun dari Carbon -->
            <h3 class="text-2xl font-extrabold text-text-dark">
                {{ $calDate->locale('id')->isoFormat('MMMM Y') }}
            </h3>

            <!-- Tombol Navigasi Bulan (Cukup pakai link URL biasa) -->
            <div class="flex items-center gap-1.5 ml-2">
                <a href="?year={{ $calDate->copy()->subMonth()->year }}&month={{ $calDate->copy()->subMonth()->month }}"
                    title="Bulan Sebelumnya"
                    class="w-8 h-8 border-2 border-text-dark rounded-xl bg-bg-main text-text-dark font-extrabold text-xs grid place-items-center hover:bg-primary-orange hover:text-white">
                    ◀
                </a>
                <a href="?year={{ now()->year }}&month={{ now()->month }}" title="Hari Ini"
                    class="px-2.5 h-8 border-2 border-text-dark rounded-xl bg-bg-main text-text-dark font-extrabold text-xs grid place-items-center hover:bg-primary-orange hover:text-white">
                    Hari Ini
                </a>
                <a href="?year={{ $calDate->copy()->addMonth()->year }}&month={{ $calDate->copy()->addMonth()->month }}"
                    title="Bulan Berikutnya"
                    class="w-8 h-8 border-2 border-text-dark rounded-xl bg-bg-main text-text-dark font-extrabold text-xs grid place-items-center hover:bg-primary-orange hover:text-white">
                    ▶
                </a>
            </div>
        </div>
        <div class="calendar-legend flex gap-4 text-xs font-bold">
            <span class="text-cat-sekolah">● Sekolah</span>
            <span class="text-cat-pribadi">● Pribadi</span>
            <span class="text-cat-acara">● Acara OSIS</span>
        </div>
    </div>

    <!-- Header Nama Hari -->
    <div class="calendar-grid grid grid-cols-7 gap-2.5 text-center">
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Minggu</div>
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Senin</div>
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Selasa</div>
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Rabu</div>
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Kamis</div>
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Jumat</div>
        <div class="cal-day-head text-xs font-extrabold text-text-muted pb-3 uppercase tracking-wider">Sabtu</div>
    </div>

    <!-- Kotak-Kotak Tanggal (Server-Side Blade Rendering) -->
    <div class="calendar-grid grid grid-cols-7 gap-2.5 text-center">
        <!-- 1. Kotak Kosong Padding Awal Bulan -->
        @for ($i = 0; $i < ($firstDayOfWeek ?? 0); $i++)
            <div class="cal-cell bg-transparent border-none shadow-none pointer-events-none"></div>
        @endfor

        <!-- 2. Kotak Tanggal 1 s/d Hari Terakhir Bulan Ini -->
        @for ($day = 1; $day <= ($daysInMonth ?? 30); $day++)
            @php
                $dateString = $calDate->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                $isToday = $dateString === now()->toDateString();
                $dayAgendas = $calAgendas[$dateString] ?? collect();
            @endphp
            <div
                class="cal-cell min-h-[75px] lg:min-h-[105px] rounded-xl p-1.5 lg:p-2 text-sm font-bold flex flex-col items-start relative border-2 transition-all duration-200 {{ $isToday ? 'border-primary-orange bg-white shadow-[0_0_0_3px_#FFF0ED]' : 'border-border-custom bg-bg-main hover:border-text-dark hover:bg-white hover:-translate-y-0.5' }}">
                <span
                    class="cal-day-number text-xs mb-1 {{ $isToday ? 'bg-primary-orange text-white w-6 h-6 rounded-full grid place-items-center font-extrabold' : 'text-text-dark font-extrabold' }}">
                    {{ $day }}
                </span>

                <!-- List Agenda Pada Tanggal Tersebut -->
                @if ($dayAgendas->isNotEmpty())
                    <div class="cal-task-list w-full flex flex-col gap-1 overflow-y-auto max-h-[65px] mt-1">
                        @foreach ($dayAgendas as $item)
                            <div class="cal-task-pill text-[0.65rem] lg:text-[0.7rem] px-1.5 py-0.5 rounded-md font-bold text-left whitespace-nowrap overflow-hidden text-ellipsis w-full border {{ $item->category === 'sekolah' ? 'bg-cat-sekolah-bg text-cat-sekolah border-red-200' : 'bg-cat-pribadi-bg text-cat-pribadi border-emerald-200' }}"
                                title="{{ $item->title }}">
                                {{ $item->title }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endfor
    </div>
</div>

<!-- TAB 2: TO-DO LIST -->
<div id="view-tugas"
    class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div
        class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
        Daftar Tugas Belum Selesai
        <span id="pending-count"
            class="bg-primary-light text-primary-orange px-3.5 py-1 rounded-full text-xs border border-primary-orange">
            {{ isset($pendingTasks) ? $pendingTasks->count() : 0 }}
        </span>
    </div>
    <div id="pending-tasks-list" class="task-list flex flex-col gap-4">
        @if (isset($pendingTasks) && $pendingTasks->count() > 0)
            @php
                $borderCategory = [
                    'sekolah' => 'border-l-cat-sekolah',
                    'pribadi' => 'border-l-cat-pribadi',
                ];
                $tagPriority = [
                    'tinggi' => 'bg-red-100 text-prio-tinggi border-red-300',
                    'sedang' => 'bg-amber-100 text-prio-sedang border-amber-200',
                    'rendah' => 'bg-emerald-100 text-prio-rendah border-emerald-300',
                ];
            @endphp
            @foreach ($pendingTasks as $task)
                <div id="task-row-{{ $task->id }}"
                    class="task-item border-l-6 {{ $borderCategory[$task->category] ?? 'border-l-primary-orange' }} p-5 rounded-2xl bg-bg-main border-2 border-border-custom flex justify-between items-center gap-4 transition-all duration-200 hover:border-text-dark hover:bg-white hover:shadow-pop hover:-translate-y-0.5">
                    <div class="task-info">
                        <h4 class="text-lg font-extrabold mb-1 text-text-dark">{{ $task->title }}</h4>
                        <p class="text-sm text-text-muted font-semibold">Tenggat Waktu: <strong
                                class="text-text-dark">{{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') }}</strong>
                        </p>
                        <div class="task-tags flex gap-2 mt-3 flex-wrap">
                            <span
                                class="tag text-[0.72rem] px-3 py-1 rounded-full font-extrabold tracking-wider border {{ $tagPriority[$task->priority] ?? $tagPriority['sedang'] }}">PRIORITAS
                                {{ strtoupper($task->priority) }}</span>
                            <span
                                class="tag text-[0.72rem] px-3 py-1 rounded-full font-extrabold tracking-wider border bg-border-custom text-text-dark border-transparent">{{ strtoupper($task->category) }}</span>
                        </div>
                    </div>
                    <div class="task-actions flex gap-2.5 items-center shrink-0">
                        <button
                            class="btn-action border-2 border-text-dark rounded-full w-10 h-10 cursor-pointer grid place-items-center font-extrabold text-base transition-all duration-200 shadow-pop-sm bg-white text-text-dark hover:bg-cat-pribadi hover:text-white hover:scale-110"
                            onclick="toggleTaskComplete({{ $task->id }})" title="Tandai Selesai">✓</button>
                        <button
                            class="btn-action border-2 border-text-dark rounded-full w-10 h-10 cursor-pointer grid place-items-center font-extrabold text-base transition-all duration-200 shadow-pop-sm bg-white text-text-dark hover:bg-red-500 hover:text-white hover:scale-110"
                            onclick="deleteTask({{ $task->id }})" title="Hapus Tugas">🗑️</button>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-text-muted p-10 font-semibold">Semua tugas telah selesai dikerjakan! 🎉</p>
        @endif
    </div>
</div>

<!-- TAB 3: ACARA SEKOLAH -->
<div id="view-acara"
    class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200">
    <div
        class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
        Acara & Agenda Kegiatan Sekolah / OSIS</div>
    <div id="school-events-list" class="task-list flex flex-col gap-4">
        @if (isset($schoolEvents) && $schoolEvents->count() > 0)
            @foreach ($schoolEvents as $event)
                <div
                    class="task-item border-l-6 border-l-cat-acara p-5 rounded-2xl bg-bg-main border-2 border-border-custom flex justify-between items-center gap-4 transition-all duration-200 hover:border-text-dark hover:bg-white hover:shadow-pop hover:-translate-y-0.5">
                    <div class="task-info">
                        <h4 class="text-lg font-extrabold mb-1 text-text-dark">{{ $event->title }}</h4>
                        <p class="text-sm text-text-muted font-semibold">Tanggal Pelaksanaan: <strong
                                class="text-text-dark">{{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}</strong>
                        </p>
                        @if ($event->description)
                            <p class="text-xs text-text-muted mt-1 font-medium">{{ $event->description }}</p>
                        @endif
                        <div class="task-tags flex gap-2 mt-3 flex-wrap">
                            <span
                                class="tag text-[0.72rem] px-3 py-1 rounded-full font-extrabold tracking-wider border bg-cat-acara-bg text-cat-acara border-purple-200">ACARA
                                SEKOLAH / OSIS</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-text-muted p-10 font-semibold">Belum ada acara sekolah yang diselenggarakan.</p>
        @endif
    </div>
</div>

<!-- TAB 4: TAMBAH AGENDA -->
<div id="view-tambah"
    class="card hidden bg-bg-card border-2 border-text-dark rounded-3xl p-8 shadow-pop-lg mb-8 transition-all duration-200 max-w-[650px] mx-auto">
    <div
        class="card-title text-xl font-extrabold tracking-tight mb-6 flex justify-between items-center flex-wrap gap-3 text-text-dark">
        Tambah Tugas / Kegiatan Baru</div>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Judul Agenda /
                Tugas</label>
            <input type="text" name="title" id="task-title" placeholder="Misal: Tugas Matematika / Latihan Futsal"
                required
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
        </div>

        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Kategori</label>
            <select name="category" id="task-category"
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                <option value="sekolah">Sekolah / Akademis</option>
                <option value="pribadi" selected>Kegiatan Pribadi</option>
            </select>
        </div>

        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tenggat Waktu /
                Tanggal</label>
            <input type="date" name="deadline" id="task-deadline" required
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
        </div>

        <div class="form-group mb-5">
            <label class="block text-xs font-extrabold mb-2 text-text-dark uppercase tracking-wider">Tingkat
                Prioritas</label>
            <select name="priority" id="task-priority"
                class="w-full px-4 py-3.5 border-2 border-border-custom rounded-xl text-sm font-semibold outline-none transition-all duration-200 bg-bg-main text-text-dark focus:border-text-dark focus:bg-white focus:shadow-pop-3px">
                <option value="tinggi">Tinggi</option>
                <option value="sedang" selected>Sedang</option>
                <option value="rendah">Rendah</option>
            </select>
        </div>

        <button type="submit"
            class="btn-primary w-full px-5 py-4 bg-primary-orange text-white border-2 border-text-dark rounded-xl font-extrabold text-base cursor-pointer transition-all duration-200 shadow-pop inline-flex items-center justify-center gap-2 hover:bg-primary-hover hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-pop-lg active:translate-x-0.5 active:translate-y-0.5 active:shadow-pop-sm">
            + Masukkan Ke Kalender
        </button>
    </form>
</div>
