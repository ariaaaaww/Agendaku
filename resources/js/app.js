const defaultUsers = [
    {
        id: 1,
        fullname: "Budi Santoso",
        username: "siswa",
        password: "123",
        role: "siswa",
    },
    {
        id: 2,
        fullname: "Administrator",
        username: "admin",
        password: "123",
        role: "admin",
    },
    {
        id: 3,
        fullname: "Pak Guruh, M.Pd",
        username: "guru",
        password: "123",
        role: "guru",
    },
    {
        id: 4,
        fullname: "Pengurus OSIS",
        username: "osis",
        password: "123",
        role: "osis",
    },
];

function getTodayDateString() {
    const d = new Date();
    return d.toISOString().split("T")[0];
}

function getTomorrowDateString() {
    const d = new Date();
    d.setDate(d.getDate() + 1);
    return d.toISOString().split("T")[0];
}

const defaultTasks = [
    {
        id: 101,
        userId: 1,
        title: "Tugas Matematika Bab 3",
        category: "sekolah",
        deadline: getTomorrowDateString(),
        priority: "tinggi",
        completed: false,
        createdBy: "Siswa",
    },
    {
        id: 102,
        userId: 1,
        title: "Latihan Futsal",
        category: "pribadi",
        deadline: getTodayDateString(),
        priority: "sedang",
        completed: true,
        createdBy: "Siswa",
    },
    {
        id: 103,
        userId: 0,
        title: "Classmeet Futsal & Seni",
        category: "acara",
        deadline: getTomorrowDateString(),
        priority: "sedang",
        completed: false,
        createdBy: "OSIS",
    },
];

// Inisialisasi awal ke LocalStorage
let storedUsers = JSON.parse(localStorage.getItem("users"));
if (!storedUsers || storedUsers.length < 4) {
    localStorage.setItem("users", JSON.stringify(defaultUsers));
}

if (!localStorage.getItem("tasks"))
    localStorage.setItem("tasks", JSON.stringify(defaultTasks));
if (!localStorage.getItem("announcement"))
    localStorage.setItem(
        "announcement",
        "Ujian Tengah Semester dimulai tanggal 25 mendatang. Diharapkan siswa mempersiapkan diri!",
    );

let currentUser = JSON.parse(sessionStorage.getItem("currentUser")) || null;

/* --- MOBILE MENU TOGGLE --- */
function toggleMobileMenu() {
    const header = document.getElementById("app-header");
    const overlay = document.getElementById("sidebar-overlay");
    const navTabs = document.getElementById("app-tab-nav");
    const navUserInfo = document.getElementById("nav-user-info");

    overlay.classList.toggle("hidden");
    if (navTabs) navTabs.classList.toggle("max-lg:!left-0");
    if (navUserInfo) navUserInfo.classList.toggle("max-lg:!left-0");
}

function closeMobileMenu() {
    const overlay = document.getElementById("sidebar-overlay");
    const navTabs = document.getElementById("app-tab-nav");
    const navUserInfo = document.getElementById("nav-user-info");

    if (overlay) overlay.classList.add("hidden");
    if (navTabs) navTabs.classList.remove("max-lg:!left-0");
    if (navUserInfo) navUserInfo.classList.remove("max-lg:!left-0");
}

/* --- PAGE & TAB SWITCHING --- */
function switchPage(pageName) {
    const appHeader = document.getElementById("app-header");
    const container = document.querySelector("main");

    document.getElementById("page-login").classList.add("hidden");
    document.getElementById("page-signup").classList.add("hidden");
    document.getElementById("page-dashboard").classList.add("hidden");

    if (pageName === "login" || pageName === "signup") {
        if (appHeader) appHeader.classList.add("hidden");
        if (container) container.classList.add("auth-mode");
    } else {
        if (appHeader) appHeader.classList.remove("hidden");
        if (container) container.classList.remove("auth-mode");
    }

    if (pageName === "login")
        document.getElementById("page-login").classList.remove("hidden");
    if (pageName === "signup")
        document.getElementById("page-signup").classList.remove("hidden");
    if (pageName === "dashboard") {
        document.getElementById("page-dashboard").classList.remove("hidden");
        renderDashboard();
        switchTab("kalender");
    }

    updateNavHeader();
    closeMobileMenu();
}

function switchTab(tabName) {
    document.getElementById("view-kalender").classList.add("hidden");
    document.getElementById("view-tugas").classList.add("hidden");
    document.getElementById("view-acara").classList.add("hidden");
    document.getElementById("view-tambah").classList.add("hidden");
    document.getElementById("view-role-panel").classList.add("hidden");

    document.getElementById("tab-btn-kalender").classList.remove("active");
    document.getElementById("tab-btn-tugas").classList.remove("active");
    document.getElementById("tab-btn-acara").classList.remove("active");
    document.getElementById("tab-btn-tambah").classList.remove("active");
    document.getElementById("tab-btn-role").classList.remove("active");

    const alertBanner = document.getElementById("alert-banner-container");
    if (alertBanner) {
        if (tabName === "tambah") {
            alertBanner.classList.add("hidden");
        } else {
            alertBanner.classList.remove("hidden");
        }
    }

    if (tabName === "kalender") {
        document.getElementById("view-kalender").classList.remove("hidden");
        document.getElementById("tab-btn-kalender").classList.add("active");
    }
    if (tabName === "tugas") {
        document.getElementById("view-tugas").classList.remove("hidden");
        document.getElementById("tab-btn-tugas").classList.add("active");
    }
    if (tabName === "acara") {
        document.getElementById("view-acara").classList.remove("hidden");
        document.getElementById("tab-btn-acara").classList.add("active");
    }
    if (tabName === "tambah") {
        document.getElementById("view-tambah").classList.remove("hidden");
        document.getElementById("tab-btn-tambah").classList.add("active");
    }
    if (tabName === "role-panel") {
        document.getElementById("view-role-panel").classList.remove("hidden");
        document.getElementById("tab-btn-role").classList.add("active");
        renderRolePanel();
    }

    closeMobileMenu();
}

function updateNavHeader() {
    const nav = document.getElementById("nav-user-info");
    const tabNav = document.getElementById("app-tab-nav");
    const roleBtn = document.getElementById("tab-btn-role");

    if (currentUser) {
        nav.classList.remove("hidden");
        nav.classList.add("flex");
        tabNav.classList.remove("hidden");
        tabNav.classList.add("flex");
        document.getElementById("user-display-name").innerText =
            currentUser.fullname;

        const avatarEl = document.getElementById("user-avatar-initial");
        if (avatarEl)
            avatarEl.innerText = currentUser.fullname.charAt(0).toUpperCase();

        const roleBadge = document.getElementById("user-role-badge");
        roleBadge.innerText = currentUser.role;

        // Custom color rules for role badge dynamically using Tailwind CSS
        const roleColors = {
            siswa: "bg-primary-light text-primary-orange border-primary-orange/30",
            admin: "bg-amber-100 text-amber-700 border-amber-500/30",
            guru: "bg-green-100 text-green-700 border-green-600/30",
            osis: "bg-purple-100 text-purple-700 border-purple-600/30",
        };

        roleBadge.className = `badge-role inline-block px-2.5 py-0.5 rounded-full text-[0.7rem] font-extrabold uppercase tracking-wider w-fit mt-0.5 border ${roleColors[currentUser.role] || roleColors.siswa}`;

        if (currentUser.role !== "siswa") {
            roleBtn.classList.remove("hidden");
            roleBtn.classList.add("flex");
            if (currentUser.role === "admin")
                roleBtn.innerHTML = "<span>⚙️</span> Panel Admin";
            if (currentUser.role === "guru")
                roleBtn.innerHTML = "<span>👨‍🏫</span> Panel Guru";
            if (currentUser.role === "osis")
                roleBtn.innerHTML = "<span>🌟</span> Panel OSIS";
        } else {
            roleBtn.classList.add("hidden");
        }
    } else {
        nav.classList.add("hidden");
        tabNav.classList.add("hidden");
    }
}

/* --- AUTHENTICATION --- */
function handleLogin(e) {
    e.preventDefault();
    const u = document.getElementById("login-username").value.trim();
    const p = document.getElementById("login-password").value.trim();

    const users = JSON.parse(localStorage.getItem("users"));
    const found = users.find(
        (user) =>
            user.username.toLowerCase() === u.toLowerCase() &&
            user.password === p,
    );

    if (found) {
        currentUser = found;
        sessionStorage.setItem("currentUser", JSON.stringify(currentUser));
        switchPage("dashboard");
    } else {
        alert(
            "Username atau Password salah! Pastikan menggunakan password '123'",
        );
    }
}

function handleSignup(e) {
    e.preventDefault();
    const fullname = document.getElementById("signup-fullname").value;
    const username = document.getElementById("signup-username").value.trim();
    const role = document.getElementById("signup-role").value;
    const password = document.getElementById("signup-password").value;

    const users = JSON.parse(localStorage.getItem("users"));
    if (
        users.some((u) => u.username.toLowerCase() === username.toLowerCase())
    ) {
        alert("Username telah dipakai!");
        return;
    }

    const newUser = { id: Date.now(), fullname, username, password, role };
    users.push(newUser);
    localStorage.setItem("users", JSON.stringify(users));

    alert("Pendaftaran berhasil, silakan masuk.");
    switchPage("login");
}

function logout() {
    currentUser = null;
    sessionStorage.removeItem("currentUser");
    switchPage("login");
}

/* --- DASHBOARD LOGIC --- */
function handleAddTask(e) {
    e.preventDefault();
    const title = document.getElementById("task-title").value;
    const category = document.getElementById("task-category").value;
    const deadline = document.getElementById("task-deadline").value;
    const priority = document.getElementById("task-priority").value;

    const tasks = JSON.parse(localStorage.getItem("tasks"));
    const newTask = {
        id: Date.now(),
        userId: currentUser.id,
        title,
        category,
        deadline,
        priority,
        completed: false,
        createdBy: currentUser.fullname,
    };

    tasks.push(newTask);
    localStorage.setItem("tasks", JSON.stringify(tasks));

    e.target.reset();
    renderDashboard();
    switchTab("kalender");
}

function toggleTaskComplete(taskId) {
    let tasks = JSON.parse(localStorage.getItem("tasks"));
    tasks = tasks.map((t) => {
        if (t.id === taskId) t.completed = !t.completed;
        return t;
    });
    localStorage.setItem("tasks", JSON.stringify(tasks));
    renderDashboard();
}

function deleteTask(taskId) {
    if (confirm("Apakah Anda yakin ingin menghapus agenda ini?")) {
        let tasks = JSON.parse(localStorage.getItem("tasks"));
        tasks = tasks.filter((t) => t.id !== taskId);
        localStorage.setItem("tasks", JSON.stringify(tasks));

        renderDashboard();
        if (currentUser.role !== "siswa") renderRolePanel();
    }
}

function renderDashboard() {
    const allTasks = JSON.parse(localStorage.getItem("tasks"));

    // Pengumuman
    const announcement = localStorage.getItem("announcement");
    const annBox = document.getElementById("announcement-banner");
    if (announcement) {
        annBox.classList.remove("hidden");
        document.getElementById("announcement-text").innerText = announcement;
    } else {
        annBox.classList.add("hidden");
    }

    // Filter Tugas sesuai Role
    let userTasks;
    if (
        currentUser.role === "admin" ||
        currentUser.role === "guru" ||
        currentUser.role === "osis"
    ) {
        userTasks = allTasks;
    } else {
        userTasks = allTasks.filter(
            (t) =>
                t.userId === currentUser.id ||
                t.category === "acara" ||
                t.userId === 0,
        );
    }

    // 1. Alert Esok Hari
    const tomorrowStr = getTomorrowDateString();
    const tomorrowTasks = userTasks.filter(
        (t) => t.deadline === tomorrowStr && !t.completed,
    );
    const alertEl = document.getElementById("alert-tomorrow-text");
    if (tomorrowTasks.length > 0) {
        alertEl.innerText = `Kamu memiliki ${tomorrowTasks.length} agenda/tugas untuk esok hari: "${tomorrowTasks[0].title}"${tomorrowTasks.length > 1 ? " dan lainnya." : "."}`;
    } else {
        alertEl.innerText =
            "Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!";
    }

    // 2. Render List Tugas Pending
    const pendingTasks = userTasks.filter(
        (t) => !t.completed && t.category !== "acara",
    );
    document.getElementById("pending-count").innerText = pendingTasks.length;
    const pendingListEl = document.getElementById("pending-tasks-list");
    pendingListEl.innerHTML = "";

    const borderCategory = {
        sekolah: "border-l-cat-sekolah",
        pribadi: "border-l-cat-pribadi",
        acara: "border-l-cat-acara",
    };

    const tagPriority = {
        tinggi: "bg-red-100 text-prio-tinggi border-red-300",
        sedang: "bg-amber-100 text-prio-sedang border-amber-200",
        rendah: "bg-emerald-100 text-prio-rendah border-emerald-300",
    };

    if (pendingTasks.length === 0) {
        pendingListEl.innerHTML = `<p class="text-center text-text-muted p-10 font-semibold">Semua tugas telah selesai dikerjakan! 🎉</p>`;
    } else {
        pendingTasks.forEach((task) => {
            pendingListEl.innerHTML += `
            <div class="task-item border-l-6 ${borderCategory[task.category] || "border-l-primary-orange"} p-5 rounded-2xl bg-bg-main border-2 border-border-custom flex justify-between items-center gap-4 transition-all duration-200 hover:border-text-dark hover:bg-white hover:shadow-pop hover:-translate-y-0.5">
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

    // 3. Render Acara OSIS / Sekolah
    const eventsList = allTasks.filter((t) => t.category === "acara");
    const eventsListEl = document.getElementById("school-events-list");
    eventsListEl.innerHTML = "";

    if (eventsList.length === 0) {
        eventsListEl.innerHTML = `<p class="text-center text-text-muted p-10 font-semibold">Belum ada acara sekolah yang diselenggarakan.</p>`;
    } else {
        eventsList.forEach((event) => {
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

    // 4. Render Kalender
    renderCalendar(userTasks);
}

function renderCalendar(userTasks) {
    const calCellsEl = document.getElementById("calendar-cells");
    calCellsEl.innerHTML = "";

    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();

    const monthNames = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    document.getElementById("cal-month-year").innerText =
        `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
        calCellsEl.innerHTML += `<div class="cal-cell bg-transparent border-none shadow-none pointer-events-none"></div>`;
    }

    const pillPills = {
        sekolah: "bg-cat-sekolah-bg text-cat-sekolah border-blue-200",
        pribadi: "bg-cat-pribadi-bg text-cat-pribadi border-emerald-200",
        acara: "bg-cat-acara-bg text-cat-acara border-purple-200",
    };

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
        const isToday = dateStr === getTodayDateString();

        const dayTasks = userTasks.filter(
            (t) => t.deadline === dateStr && !t.completed,
        );

        let tasksHtml =
            '<div class="cal-task-list w-full flex flex-col gap-1 overflow-y-auto max-h-[65px]">';
        dayTasks.forEach((t) => {
            tasksHtml += `<div class="cal-task-pill text-[0.6rem] lg:text-[0.7rem] px-1.5 py-0.5 lg:px-1.5 lg:py-1 rounded-md font-bold text-left whitespace-nowrap overflow-hidden text-ellipsis w-full border ${pillPills[t.category] || pillPills.sekolah}" title="${t.title}">${t.title}</div>`;
        });
        tasksHtml += "</div>";

        const todayClasses = isToday
            ? "border-primary-orange bg-white shadow-[0_0_0_3px_#FFF0ED]"
            : "border-border-custom bg-bg-main hover:border-text-dark hover:bg-white hover:-translate-y-0.5 hover:shadow-pop-3px";

        calCellsEl.innerHTML += `
          <div class="cal-cell min-h-[70px] lg:min-h-[105px] rounded-xl p-1 lg:p-2 text-sm lg:text-base font-bold flex flex-col items-start relative border-2 transition-all duration-200 ${todayClasses}">
            <span class="cal-day-number text-xs font-extrabold text-text-dark mb-1.5 ${isToday ? "bg-primary-orange text-white w-6 h-6 rounded-full grid place-items-center font-extrabold" : ""}">${day}</span>
            ${dayTasks.length > 0 ? tasksHtml : ""}
          </div>
        `;
    }
}

/* --- ROLE SPECIFIC PANELS --- */
function renderRolePanel() {
    document.getElementById("panel-admin").classList.add("hidden");
    document.getElementById("panel-guru").classList.add("hidden");
    document.getElementById("panel-osis").classList.add("hidden");

    if (currentUser.role === "admin") {
        document.getElementById("panel-admin").classList.remove("hidden");
        renderAdminData();
    }
    if (currentUser.role === "guru") {
        document.getElementById("panel-guru").classList.remove("hidden");
        renderGuruData();
    }
    if (currentUser.role === "osis") {
        document.getElementById("panel-osis").classList.remove("hidden");
        renderOsisData();
    }
}

// Admin
function renderAdminData() {
    const users = JSON.parse(localStorage.getItem("users"));
    const tasks = JSON.parse(localStorage.getItem("tasks"));

    const siswas = users.filter((u) => u.role === "siswa");
    const completedTasks = tasks.filter((t) => t.completed).length;
    const pendingTasks = tasks.filter(
        (t) => !t.completed && t.category !== "acara",
    ).length;

    document.getElementById("admin-stat-users").innerText = siswas.length;
    document.getElementById("admin-stat-completed").innerText = completedTasks;
    document.getElementById("admin-stat-pending").innerText = pendingTasks;

    const userTable = document.getElementById("admin-user-table-body");
    userTable.innerHTML = "";
    siswas.forEach((siswa) => {
        const uTasks = tasks.filter((t) => t.userId === siswa.id);
        const uDone = uTasks.filter((t) => t.completed).length;
        const uPending = uTasks.filter((t) => !t.completed).length;

        userTable.innerHTML += `
          <tr class="border-b border-border-custom">
            <td class="p-4.5 font-extrabold">${siswa.fullname} (${siswa.username})</td>
            <td class="p-4.5 font-extrabold text-cat-pribadi">${uDone}</td>
            <td class="p-4.5 font-extrabold text-prio-tinggi">${uPending}</td>
            <td class="p-4.5 font-bold"><strong>${uTasks.length}</strong></td>
          </tr>
        `;
    });

    const eventsTable = document.getElementById("admin-events-table-body");
    eventsTable.innerHTML = "";
    const events = tasks.filter((t) => t.category === "acara");
    events.forEach((ev) => {
        eventsTable.innerHTML += `
          <tr class="border-b border-border-custom">
            <td class="p-4.5 font-extrabold">${ev.title}</td>
            <td class="p-4.5">${ev.deadline}</td>
            <td class="p-4.5">${ev.createdBy || "OSIS"}</td>
          </tr>
        `;
    });
}

// Guru
function handlePostAnnouncement(e) {
    e.preventDefault();
    const txt = document.getElementById("announcement-input").value;
    localStorage.setItem("announcement", txt);
    alert("Pengumuman telah diperbarui!");
    renderDashboard();
}

function handleGuruAddTask(e) {
    e.preventDefault();
    const title = document.getElementById("guru-task-title").value;
    const category = document.getElementById("guru-task-type").value;
    const deadline = document.getElementById("guru-task-date").value;

    const users = JSON.parse(localStorage.getItem("users"));
    const tasks = JSON.parse(localStorage.getItem("tasks"));

    if (category === "acara") {
        tasks.push({
            id: Date.now(),
            userId: 0,
            title,
            category: "acara",
            deadline,
            priority: "tinggi",
            completed: false,
            createdBy: currentUser.fullname,
        });
    } else {
        const siswas = users.filter((u) => u.role === "siswa");
        siswas.forEach((s) => {
            tasks.push({
                id: Date.now() + Math.random(),
                userId: s.id,
                title: `${title} (Tugas Guru)`,
                category: "sekolah",
                deadline,
                priority: "tinggi",
                completed: false,
                createdBy: currentUser.fullname,
            });
        });
    }

    localStorage.setItem("tasks", JSON.stringify(tasks));
    alert("Tugas / Acara berhasil ditambahkan ke seluruh siswa & kalender!");
    e.target.reset();
    renderDashboard();
    renderGuruData();
}

function renderGuruData() {
    const tasks = JSON.parse(localStorage.getItem("tasks"));
    const table = document.getElementById("guru-all-tasks-table");
    table.innerHTML = "";

    tasks.forEach((t) => {
        table.innerHTML += `
          <tr class="border-b border-border-custom">
            <td class="p-4.5 font-extrabold">${t.title}</td>
            <td class="p-4.5 font-bold uppercase">${t.category}</td>
            <td class="p-4.5">${t.deadline}</td>
            <td class="p-4.5">${t.createdBy || "System"}</td>
            <td class="p-4.5"><button class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm" onclick="deleteTask(${t.id})">Hapus</button></td>
          </tr>
        `;
    });
}

// OSIS
function handleOsisAddEvent(e) {
    e.preventDefault();
    const title = document.getElementById("osis-event-title").value;
    const deadline = document.getElementById("osis-event-date").value;

    const tasks = JSON.parse(localStorage.getItem("tasks"));
    tasks.push({
        id: Date.now(),
        userId: 0,
        title,
        category: "acara",
        deadline,
        priority: "sedang",
        completed: false,
        createdBy: "Pengurus OSIS",
    });

    localStorage.setItem("tasks", JSON.stringify(tasks));
    alert("Acara OSIS berhasil dipublikasikan!");
    e.target.reset();
    renderDashboard();
    renderOsisData();
}

function renderOsisData() {
    const tasks = JSON.parse(localStorage.getItem("tasks")).filter(
        (t) => t.category === "acara",
    );
    const table = document.getElementById("osis-events-table");
    table.innerHTML = "";

    tasks.forEach((ev) => {
        table.innerHTML += `
          <tr class="border-b border-border-custom">
            <td class="p-4.5 font-extrabold">${ev.title}</td>
            <td class="p-4.5">${ev.deadline}</td>
            <td class="p-4.5"><button class="bg-red-100 text-red-600 border-2 border-red-300 rounded-lg px-3 py-1.5 text-xs font-extrabold cursor-pointer transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-text-dark hover:shadow-pop-sm" onclick="deleteTask(${ev.id})">Hapus</button></td>
          </tr>
        `;
    });
}

/* --- INIT --- */
window.onload = function () {
    localStorage.removeItem("theme");
    document.body.classList.remove("dark-mode");
    if (currentUser) {
        switchPage("dashboard");
    } else {
        switchPage("login");
    }
}; //
