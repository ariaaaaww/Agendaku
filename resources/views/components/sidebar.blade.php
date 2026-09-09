<!-- FIXED VERTICAL SIDEBAR / HEADER -->
<header
    class="app-header fixed top-0 left-0 bottom-0 w-full max-lg:h-[72px] lg:w-[280px] bg-bg-sidebar max-lg:border-b-2 lg:border-r-2 
    border-text-dark lg:border-border-custom flex flex-row lg:flex-col justify-between items-center lg:items-stretch p-3.5 lg:py-8 lg:px-8 z-[1000] lg:z-[100] 
    transition-all duration-300 shadow-sm"
    id="app-header">

    <div class="flex items-center justify-between w-full">
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
        <button id="tab-btn-role"
            class="nav-tab-btn hidden flex items-center gap-3 p-3.5 border-2 border-transparent bg-transparent rounded-2xl font-bold text-base text-text-muted cursor-pointer transition-all duration-200 text-left w-full hover:bg-bg-main hover:text-text-dark hover:translate-x-1 [&.active]:bg-primary-orange [&.active]:text-white [&.active]:border-text-dark [&.active]:shadow-pop [&.active]:translate-x-1 [&.active]:hover:bg-primary-hover"></button>
    </div>

    <!-- User Profile Box -->
    <div id="nav-user-info"
        class="user-profile-nav flex bg-bg-main border-2 border-border-custom rounded-3xl p-4 flex-col gap-3.5 mt-6 max-lg:fixed max-lg:bottom-0 max-lg:-left-full max-lg:w-[280px] max-lg:rounded-none max-lg:border-r-2 max-lg:border-text-dark max-lg:border-t-2 max-lg:border-border-custom max-lg:z-[1000] max-lg:mt-0 transition-[left] duration-300">
        <div class="user-profile-header flex items-center gap-3">
            <div class="user-avatar w-10 h-10 rounded-full bg-secondary-light border-2 border-secondary-teal grid place-items-center font-extrabold color-secondary-teal text-[1rem]"
                id="user-avatar-initial">U</div>
            <div class="user-details flex flex-col overflow-hidden">
                <span id="user-display-name"
                    class="font-extrabold text-sm text-text-dark whitespace-nowrap overflow-hidden text-ellipsis">User</span>
                <span id="user-role-badge"
                    class="badge-role inline-block px-2.5 py-1 rounded-full text-[0.7rem] font-extrabold uppercase tracking-wider w-fit mt-0.5 bg-primary-light text-primary-orange border border-primary-orange/30">Siswa</span>
            </div>
        </div>
        <button onclick="logout()"
            class="btn-logout bg-[#FF5E5E] text-white border-2 border-[#1A1A1A]/14 p-2.5 rounded-xl cursor-pointer font-extrabold text-xs transition-all w-full text-center hover:bg-white hover:text-[#FF5E5E] hover:-translate-y-0.5">Keluar
            Akun</button>
    </div>

</header>
