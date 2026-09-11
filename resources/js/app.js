const defaultUsers = [
    {
        id: 1,
        fullname: "Arianto",
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

// Auto initialize currentUser based on active route if not logged in
if (!currentUser) {
    const path = window.location.pathname;
    if (path.includes("/teachers")) {
        currentUser = defaultUsers[2]; // Guru
    } else if (path.includes("/admin")) {
        currentUser = defaultUsers[1]; // Admin
    } else if (path.includes("/council")) {
        currentUser = defaultUsers[3]; // OSIS
    } else if (path.includes("/students") || path === "/") {
        currentUser = defaultUsers[0]; // Siswa
    }
    if (currentUser) {
        sessionStorage.setItem("currentUser", JSON.stringify(currentUser));
    }
}

function getRoleBasePath() {
    if (currentUser) {
        if (currentUser.role === "guru") return "/teachers";
        if (currentUser.role === "osis") return "/council";
        if (currentUser.role === "admin") return "/admin";
        return "/students";
    }
    const path = window.location.pathname;
    if (path.includes("/teachers")) return "/teachers";
    if (path.includes("/council")) return "/council";
    if (path.includes("/admin")) return "/admin";
    return "/students";
}

function goToRoleHome() {
    const base = getRoleBasePath();
    if (window.location.pathname === base) {
        if (base === "/students") {
            switchTab("kalender");
        } else {
            switchTab("role-panel");
        }
    } else {
        window.location.href = base;
    }
}

/* --- MOBILE MENU TOGGLE --- */
function toggleMobileMenu() {
    const overlay = document.getElementById("sidebar-overlay");
    const navTabs = document.getElementById("app-tab-nav");
    const navUserInfo = document.getElementById("nav-user-info");

    if (overlay) overlay.classList.toggle("hidden");
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

/* --- HEADER & NAVIGATION UPDATE --- */
function updateNavHeader() {
    const nav = document.getElementById("nav-user-info");
    const tabNav = document.getElementById("app-tab-nav");
    const roleBtn = document.getElementById("tab-btn-role");

    if (currentUser) {
        if (nav) {
            nav.classList.remove("hidden");
            nav.classList.add("flex");
        }
        if (tabNav) {
            tabNav.classList.remove("hidden");
            tabNav.classList.add("flex");
        }
        const nameEl = document.getElementById("user-display-name");
        if (nameEl) nameEl.innerText = currentUser.fullname;

        const avatarEl = document.getElementById("user-avatar-initial");
        if (avatarEl)
            avatarEl.innerText = currentUser.fullname.charAt(0).toUpperCase();

        const roleBadge = document.getElementById("user-role-badge");
        if (roleBadge) {
            roleBadge.innerText = currentUser.role;
            const roleColors = {
                siswa: "bg-primary-light text-primary-orange border-primary-orange/30",
                admin: "bg-amber-100 text-amber-700 border-amber-500/30",
                guru: "bg-green-100 text-green-700 border-green-600/30",
                osis: "bg-purple-100 text-purple-700 border-purple-600/30",
            };
            roleBadge.className = `badge-role inline-block px-2.5 py-0.5 rounded-full text-[0.7rem] font-extrabold uppercase tracking-wider w-fit mt-0.5 border ${roleColors[currentUser.role] || roleColors.siswa}`;
        }

        if (roleBtn) {
            if (currentUser.role !== "siswa") {
                roleBtn.classList.remove("hidden");
                roleBtn.classList.add("flex");
                const roleRoutes = {
                    admin: {
                        label: '<span class="icon-[clarity--administrator-line]"></span> Panel Admin',
                        url: "/admin",
                    },
                    guru: {
                        label: '<span class="icon-[ci--users]"></span>  Panel Guru',
                        url: "/teachers",
                    },
                    osis: {
                        label: '<span class="icon-[ci--users]"></span> Panel OSIS',
                        url: "/council",
                    },
                };
                const config = roleRoutes[currentUser.role];
                if (config) {
                    roleBtn.innerHTML = config.label;
                    roleBtn.onclick = function () {
                        if (window.location.pathname === config.url) {
                            switchTab("role-panel");
                        } else {
                            window.location.href = config.url;
                        }
                    };
                }
            } else {
                roleBtn.classList.add("hidden");
            }
        }
    } else {
        if (nav) nav.classList.add("hidden");
        if (tabNav) tabNav.classList.add("hidden");
    }
}

/* --- TAB SWITCHING LOGIC --- */
function switchTab(tabName) {
    const roleBase = getRoleBasePath();
    const targetEl = document.getElementById("view-" + tabName);

    // If current page doesn't have the target view, navigate to the role base URL with tab query
    if (!targetEl && tabName !== "role-panel") {
        window.location.href = roleBase + "?tab=" + tabName;
        return;
    }

    const tabs = ["kalender", "tugas", "acara", "tambah", "role-panel"];
    tabs.forEach((t) => {
        const el = document.getElementById("view-" + t);
        const btn = document.getElementById(
            t === "role-panel" ? "tab-btn-role" : "tab-btn-" + t,
        );
        if (el) el.classList.add("hidden");
        if (btn) btn.classList.remove("active");
    });

    const alertBanner = document.getElementById("alert-banner-container");
    if (alertBanner) {
        if (tabName === "tambah" || tabName === "role-panel") {
            alertBanner.classList.add("hidden");
        } else {
            alertBanner.classList.remove("hidden");
        }
    }

    const targetView = document.getElementById("view-" + tabName);
    const targetBtn = document.getElementById(
        tabName === "role-panel" ? "tab-btn-role" : "tab-btn-" + tabName,
    );
    if (targetView) targetView.classList.remove("hidden");
    if (targetBtn) targetBtn.classList.add("active");

    // Update URL in browser address bar to match role & tab
    const currentPath = window.location.pathname;
    const newUrl =
        tabName === "role-panel"
            ? currentPath
            : currentPath + "?tab=" + tabName;
    if (window.location.pathname + window.location.search !== newUrl) {
        window.history.pushState({ tab: tabName }, "", newUrl);
    }

    // Call role-specific render functions if switching to role panel
    if (tabName === "role-panel") {
        if (typeof renderOsisData === "function") renderOsisData();
        if (typeof renderGuruData === "function") renderGuruData();
        if (typeof renderAdminData === "function") renderAdminData();
    }

    closeMobileMenu();
}

/* --- AUTHENTICATION HANDLERS --- */
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
        if (currentUser.role === "admin") {
            window.location.href = "/admin";
        } else if (currentUser.role === "guru") {
            window.location.href = "/teachers";
        } else if (currentUser.role === "osis") {
            window.location.href = "/council";
        } else {
            window.location.href = "/students";
        }
    } else {
        alert("Username atau Password salah!");
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
    window.location.href = "/login";
}

function logout() {
    currentUser = null;
    sessionStorage.removeItem("currentUser");
    window.location.href = "/login";
}

function deleteTask(taskId) {
    if (confirm("Apakah Anda yakin ingin menghapus agenda ini?")) {
        let tasks = JSON.parse(localStorage.getItem("tasks"));
        tasks = tasks.filter((t) => t.id != taskId);
        localStorage.setItem("tasks", JSON.stringify(tasks));

        if (typeof renderDashboard === "function") renderDashboard();
        if (typeof renderGuruData === "function") renderGuruData();
        if (typeof renderAdminData === "function") renderAdminData();
        if (typeof renderOsisData === "function") renderOsisData();
    }
}

function toggleTaskComplete(taskId) {
    let tasks = JSON.parse(localStorage.getItem("tasks"));
    tasks = tasks.map((t) => {
        if (t.id == taskId) t.completed = !t.completed;
        return t;
    });
    localStorage.setItem("tasks", JSON.stringify(tasks));
    if (typeof renderDashboard === "function") renderDashboard();
}

/* --- DASHBOARD LOGIC (SHARED) --- */
function handleAddTask(e) {
    e.preventDefault();
    const title = document.getElementById("task-title").value;
    const category = document.getElementById("task-category").value;
    const deadline = document.getElementById("task-deadline").value;
    const priority = document.getElementById("task-priority").value;

    const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    const newTask = {
        id: Date.now(),
        userId: currentUser ? currentUser.id : 1,
        title,
        category,
        deadline,
        priority,
        completed: false,
        createdBy: currentUser ? currentUser.fullname : "Siswa",
    };

    tasks.push(newTask);
    localStorage.setItem("tasks", JSON.stringify(tasks));

    e.target.reset();
    renderDashboard();
    switchTab("kalender");
}

function renderDashboard() {
    const allTasks = JSON.parse(localStorage.getItem("tasks")) || [];

    // Announcement
    const announcement = localStorage.getItem("announcement");
    const annBox = document.getElementById("announcement-banner");
    if (annBox) {
        if (announcement) {
            annBox.classList.remove("hidden");
            const annText = document.getElementById("announcement-text");
            if (annText) annText.innerText = announcement;
        } else {
            annBox.classList.add("hidden");
        }
    }

    // Filter Tasks by Role
    let userTasks;
    if (
        currentUser &&
        (currentUser.role === "admin" ||
            currentUser.role === "guru" ||
            currentUser.role === "osis")
    ) {
        userTasks = allTasks;
    } else {
        const currentUserId = currentUser ? currentUser.id : 1;
        userTasks = allTasks.filter(
            (t) =>
                t.userId === currentUserId ||
                t.category === "acara" ||
                t.userId === 0,
        );
    }

    // Alert Tomorrow
    const tomorrowStr = getTomorrowDateString();
    const tomorrowTasks = userTasks.filter(
        (t) => t.deadline === tomorrowStr && !t.completed,
    );
    const alertEl = document.getElementById("alert-tomorrow-text");
    if (alertEl) {
        if (tomorrowTasks.length > 0) {
            alertEl.innerText = `Kamu memiliki ${tomorrowTasks.length} agenda/tugas untuk esok hari: "${tomorrowTasks[0].title}"${tomorrowTasks.length > 1 ? " dan lainnya." : "."}`;
        } else {
            alertEl.innerText =
                "Tidak ada tenggat waktu atau agenda esok hari. Semuanya terkendali!";
        }
    }

    // Pending Tasks List
    const pendingTasks = userTasks.filter(
        (t) => !t.completed && t.category !== "acara",
    );
    const pendingCountEl = document.getElementById("pending-count");
    if (pendingCountEl) pendingCountEl.innerText = pendingTasks.length;

    const pendingListEl = document.getElementById("pending-tasks-list");
    if (pendingListEl) {
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
            pendingListEl.innerHTML =
                '<p class="text-center text-text-muted p-10 font-semibold">Semua tugas telah selesai dikerjakan! 🎉</p>';
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
    }

    // School Events List
    const eventsList = allTasks.filter((t) => t.category === "acara");
    const eventsListEl = document.getElementById("school-events-list");
    if (eventsListEl) {
        eventsListEl.innerHTML = "";
        if (eventsList.length === 0) {
            eventsListEl.innerHTML =
                '<p class="text-center text-text-muted p-10 font-semibold">Belum ada acara sekolah yang diselenggarakan.</p>';
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
    }

    // Render Calendar
    renderCalendar(userTasks);
}

function renderCalendar(userTasks) {
    const calCellsEl = document.getElementById("calendar-cells");
    if (!calCellsEl) return;
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
    const monthYearEl = document.getElementById("cal-month-year");
    if (monthYearEl) monthYearEl.innerText = `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < firstDay; i++) {
        calCellsEl.innerHTML +=
            '<div class="cal-cell bg-transparent border-none shadow-none pointer-events-none"></div>';
    }

    const pillPills = {
        sekolah: "bg-cat-sekolah-bg text-cat-sekolah border-red-200",
        pribadi: "bg-cat-pribadi-bg text-cat-pribadi border-emerald-200",
        acara: "bg-cat-acara-bg text-cat-acara border-purple-200",
    };

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
        const isToday = dateStr === getTodayDateString();

        const dayTasks = (userTasks || []).filter(
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

document.addEventListener("DOMContentLoaded", function () {
    updateNavHeader();

    if (document.getElementById("view-kalender")) {
        renderDashboard();
    }

    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    const path = window.location.pathname;

    if (tabParam) {
        switchTab(tabParam);
    } else if (
        path.includes("/teachers") ||
        path.includes("/council") ||
        path.includes("/admin")
    ) {
        switchTab("role-panel");
    } else if (path.includes("/students") || path === "/") {
        switchTab("kalender");
    }
});

window.addEventListener("popstate", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    const path = window.location.pathname;
    if (tabParam) {
        switchTab(tabParam);
    } else if (
        path.includes("/teachers") ||
        path.includes("/council") ||
        path.includes("/admin")
    ) {
        switchTab("role-panel");
    } else {
        switchTab("kalender");
    }
});

// ==========================================
// 2. EXPORT KE WINDOW (TARUH DI PALING BAWAH)
// ==========================================

// Helper function & Data default
window.getTodayDateString = getTodayDateString;
window.getTomorrowDateString = getTomorrowDateString;
window.defaultUsers = defaultUsers;
window.defaultTasks = defaultTasks;
window.getRoleBasePath = getRoleBasePath;
window.goToRoleHome = goToRoleHome;

// Variabel currentUser (Getter/Setter)
Object.defineProperty(window, "currentUser", {
    get() {
        return currentUser;
    },
    set(val) {
        currentUser = val;
    },
    configurable: true,
});

// Handler Event HTML
window.handleLogin = handleLogin;
window.handleSignup = handleSignup;
window.logout = logout;
window.toggleMobileMenu = toggleMobileMenu;
window.closeMobileMenu = closeMobileMenu;
window.updateNavHeader = updateNavHeader;
window.switchTab = switchTab;
window.deleteTask = deleteTask;
window.toggleTaskComplete = toggleTaskComplete;
window.handleAddTask = handleAddTask;
window.renderDashboard = renderDashboard;
window.renderCalendar = renderCalendar;
