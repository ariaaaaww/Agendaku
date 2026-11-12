<!-- FIXED VERTICAL SIDEBAR / HEADER -->
<header
    class="app-header fixed top-0 left-0 bottom-0 w-full max-lg:h-[72px] lg:w-[280px] bg-bg-sidebar max-lg:border-b-2 lg:border-r-2 
    border-text-dark lg:border-border-custom flex flex-row lg:flex-col justify-between items-center lg:items-stretch p-3.5 lg:py-6 lg:px-6 z-[1000] lg:z-[100] 
    transition-all duration-300 shadow-sm lg:overflow-y-auto"
    id="app-header">

    <div class="flex items-center justify-between w-full shrink-0">
        <div class="brand flex items-center gap-3.5 lg:pb-6 lg:border-b-2 lg:border-dashed lg:border-border-custom group cursor-pointer"
            onclick="goToRoleHome()">
            <div
                class="brand-logo w-10 h-10 lg:w-12 lg:h-12 bg-primary-orange rounded-xl grid place-items-center text-white font-extrabold text-3xl lg:text-2xl shadow-[0_6px_16px_rgba(255,90,54,0.25)] group-hover:scale-105 transition-transform duration-300">
                A</div>
            <div class="brand-text">
                <h1 class="text-lg lg:text-xl font-extrabold tracking-tight leading-tight text-text-dark">Agendaku</h1>
                <span
                    class="text-[0.65rem] lg:text-xs font-bold text-primary-orange uppercase tracking-wider block mt-0.5">Class&Life
                    Sync</span>
            </div>
        </div>

        <!-- Mobile Hamburger Button -->
        <button
            class="mobile-menu-btn lg:hidden grid place-items-center w-11 h-11 rounded-xl border-2 border-text-dark bg-white text-xl font-extrabold cursor-pointer shadow-pop-sm"
            onclick="toggleMobileMenu()" title="Menu">☰</button>
    </div>

    <!-- Navigation Tabs -->
    <div id="app-tab-nav"
        class="nav-tabs flex flex-col gap-2.5 mt-6 w-[93%] max-lg:fixed max-lg:top-[72px] max-lg:-left-full max-lg:w-[280px] max-lg:h-[calc(100vh-72px)] max-lg:bg-white max-lg:border-r-2 max-lg:border-text-dark max-lg:p-5 max-lg:z-[999] max-lg:mt-0 max-lg:shadow-2xl transition-[left] duration-300">
        <button onclick="switchTab('kalender')" id="tab-btn-kalender"
            class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover active">
            <span class="icon-[akar-icons--calendar]">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                    <g class="nrj6pi">
                        <rect class="rn0ebg" />
                        <path class="qug2mi" />
                    </g>
                </svg>
            </span> Kalender
        </button>
        <button onclick="switchTab('tugas')" id="tab-btn-tugas"
            class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
            <span class="icon-[ci--list-check]"></span> To-Do List
        </button>
        <button onclick="switchTab('acara')" id="tab-btn-acara"
            class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
            <span class="icon-[hugeicons--ai-magic]"></span> Acara Sekolah
        </button>
        <button onclick="switchTab('tambah')" id="tab-btn-tambah"
            class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
            <span class="icon-[akar-icons--plus]"></span> Tambah Agenda
        </button>
        @auth
            @php
                $currentRole =
                    auth()->user()->role instanceof \App\Enums\UserRole
                        ? auth()->user()->role->value
                        : auth()->user()->role ?? 'student';
            @endphp

            {{-- 1. ROLE ADMIN: Dapat melihat dan mengakses semua panel khusus setiap role --}}
            @if ($currentRole === 'admin')
                <div class="my-2 border-t-2 border-dashed border-border-custom"></div>
                <div class="px-2 text-[0.65rem] font-extrabold uppercase tracking-wider text-text-muted">Akses Khusus Admin
                </div>

                {{-- Panel Admin --}}
                @if (request()->routeIs('admin.*') || request()->is('admin*'))
                    <button onclick="switchTab('role-panel')" id="tab-btn-role"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
                        <span class="icon-[clarity--administrator-line]"></span> Panel Admin
                    </button>
                @else
                    <a href="{{ route('admin.index') }}"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1">
                        <span class="icon-[clarity--administrator-line]"></span> Panel Admin
                    </a>
                @endif

                {{-- Panel Guru --}}
                @if (request()->routeIs('teachers.*') || request()->is('teacher*'))
                    <button onclick="switchTab('role-panel')" id="tab-btn-role"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
                        <span class="icon-[ci--users]"></span> Panel Guru
                    </button>
                @else
                    <a href="{{ route('teachers.index') }}"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1">
                        <span class="icon-[ci--users]"></span> Panel Guru
                    </a>
                @endif

                {{-- Panel OSIS --}}
                @if (request()->routeIs('council.*') || request()->is('council*'))
                    <button onclick="switchTab('role-panel')" id="tab-btn-role"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
                        <span class="icon-[ci--users]"></span> Panel OSIS
                    </button>
                @else
                    <a href="{{ route('council.index') }}"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1">
                        <span class="icon-[ci--users]"></span> Panel OSIS
                    </a>
                @endif

                {{-- Halaman Siswa --}}
                @if (!request()->routeIs('students.*') && !request()->is('students*'))
                    <a href="{{ route('students.index') }}"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1">
                        <span class="icon-[akar-icons--calendar]"></span> Halaman Siswa
                    </a>
                @endif

                {{-- 2. ROLE GURU: Hanya melihat Panel Guru --}}
            @elseif ($currentRole === 'teacher' || $currentRole === 'guru')
                @if (request()->routeIs('teachers.*') || request()->is('teacher*'))
                    <button onclick="switchTab('role-panel')" id="tab-btn-role"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
                        <span class="icon-[ci--users]"></span> Panel Guru
                    </button>
                @else
                    <a href="{{ route('teachers.index') }}"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1">
                        <span class="icon-[ci--users]"></span> Panel Guru
                    </a>
                @endif

                {{-- 3. ROLE OSIS: Hanya melihat Panel OSIS --}}
            @elseif ($currentRole === 'council' || $currentRole === 'osis')
                @if (request()->routeIs('council.*') || request()->is('council*'))
                    <button onclick="switchTab('role-panel')" id="tab-btn-role"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover">
                        <span class="icon-[ci--users]"></span> Panel OSIS
                    </button>
                @else
                    <a href="{{ route('council.index') }}"
                        class="nav-tab-btn flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1">
                        <span class="icon-[ci--users]"></span> Panel OSIS
                    </a>
                @endif

                {{-- 4. ROLE SISWA (student/siswa): Tidak menampilkan panel tambahan --}}
            @endif
        @endauth
    </div>

    <!-- User Profile Box -->
    <div id="nav-user-info"
    class="user-profile-nav flex bg-bg-main border-2 border-border-custom rounded-3xl p-4 flex-col gap-3.5 mt-6 shrink-0 max-lg:fixed max-lg:bottom-0 max-lg:-left-full max-lg:w-[280px] max-lg:rounded-none max-lg:border-r-2 max-lg:border-text-dark max-lg:border-t-2 max-lg:border-border-custom max-lg:z-[1000] max-lg:mt-0 transition-[left] duration-300">
        @auth
            @php
                $roleColors = [
                    'student' => 'bg-primary-light text-primary-orange border-primary-orange/30',
                    'siswa' => 'bg-primary-light text-primary-orange border-primary-orange/30',
                    'admin' => 'bg-amber-100 text-amber-700 border-amber-500/30',
                    'teacher' => 'bg-green-100 text-green-700 border-green-600/30',
                    'guru' => 'bg-green-100 text-green-700 border-green-600/30',
                    'council' => 'bg-purple-100 text-purple-700 border-purple-600/30',
                    'osis' => 'bg-purple-100 text-purple-700 border-purple-600/30',
                ];

                $userRole =
                    auth()->user()->role instanceof \App\Enums\UserRole
                        ? auth()->user()->role->value
                        : auth()->user()->role ?? 'student';

                $roleClass = $roleColors[$userRole] ?? $roleColors['student'];
            @endphp
            <div class="user-profile-header flex items-center gap-3">
                <div class="user-avatar w-10 h-10 rounded-full bg-secondary-light border-2 border-secondary-teal grid place-items-center font-extrabold color-secondary-teal text-[1rem]"
                    id="user-avatar-initial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-details flex flex-col overflow-hidden">
                    <span id="user-display-name"
                        class="font-extrabold text-sm text-text-dark whitespace-nowrap overflow-hidden text-ellipsis">{{ auth()->user()->name }}</span>
                    <span id="user-role-badge"
                        class="badge-role inline-block px-2.5 py-0.5 rounded-full text-[0.7rem] font-extrabold uppercase tracking-wider w-fit mt-0.5 border {{ $roleClass }}">{{ ucfirst($userRole) }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="w-full m-0">
                @csrf
                <button type="submit"
                    class="btn-logout bg-[#FF5E5E] text-white border-2 border-[#1A1A1A]/14 p-2.5 rounded-xl cursor-pointer font-extrabold text-xs transition-all w-full text-center hover:bg-white hover:text-[#FF5E5E] hover:-translate-y-0.5">Keluar
                    Akun</button>
            </form>
        @else
            <a href="{{ route('login') }}"
                class="btn-primary w-full p-2.5 bg-primary-orange text-white text-center font-extrabold text-xs rounded-xl shadow-pop">Masuk</a>
        @endauth
    </div>

</header>
