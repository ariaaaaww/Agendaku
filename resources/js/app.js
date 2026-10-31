/* =========================================================================
 * AGENDAKU - CLIENT SCRIPT (LARAVEL 13 + MYSQL BACKEND INTEGRATION)
 * ========================================================================= */

// State penunjuk bulan & tahun kalender
let currentCalYear = new Date().getFullYear();
let currentCalMonth = new Date().getMonth() + 1; // 1 - 12

/**
 * Helper: Ambil CSRF Token dari tag meta layout
 */
function getCSRFToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute("content") : "";
}

/**
 * Helper: Ambil string tanggal hari ini (YYYY-MM-DD)
 */
function getTodayDateString() {
    const d = new Date();
    return d.toISOString().split("T")[0];
}

/**
 * Mobile Navigation Menu Toggles
 */
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

function goToRoleHome() {
    const path = window.location.pathname;
    if (path.includes("/students")) {
        switchTab("kalender");
    } else {
        switchTab("role-panel");
    }
}

/**
 * Tab Switching Logic (Kalender, To-Do List, Acara Sekolah, Tambah Agenda, Panel Role)
 */
function switchTab(tabName) {
    const tabs = ["kalender", "tugas", "acara", "tambah", "role-panel"];

    // Hide all tabs and remove active button styling
    tabs.forEach((t) => {
        const el = document.getElementById("view-" + t);
        const btn = document.getElementById(
            t === "role-panel" ? "tab-btn-role" : "tab-btn-" + t,
        );
        if (el) el.classList.add("hidden");
        if (btn) btn.classList.remove("active");
    });

    // Toggle alert banner visibility based on active tab
    const alertBanner = document.getElementById("alert-banner-container");
    if (alertBanner) {
        if (tabName === "tambah" || tabName === "role-panel") {
            alertBanner.classList.add("hidden");
        } else {
            alertBanner.classList.remove("hidden");
        }
    }

    // Show target view
    const targetView = document.getElementById("view-" + tabName);
    const targetBtn = document.getElementById(
        tabName === "role-panel" ? "tab-btn-role" : "tab-btn-" + tabName,
    );
    if (targetView) targetView.classList.remove("hidden");
    if (targetBtn) targetBtn.classList.add("active");

    // Update browser URL query string without reloading
    const currentPath = window.location.pathname;
    const newUrl =
        tabName === "role-panel"
            ? currentPath
            : currentPath + "?tab=" + tabName;
    if (window.location.pathname + window.location.search !== newUrl) {
        window.history.pushState({ tab: tabName }, "", newUrl);
    }

    closeMobileMenu();
}

/**
 * Tambah Agenda Baru (Tab Tambah Agenda) -> Kirim ke MySQL via POST /tasks
 */
async function handleAddTask(e) {
    e.preventDefault();
    const title = document.getElementById("task-title").value.trim();
    const category = document.getElementById("task-category").value;
    const deadline = document.getElementById("task-deadline").value;
    const priority = document.getElementById("task-priority").value;

    try {
        const response = await fetch("/tasks", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCSRFToken(),
                Accept: "application/json",
            },
            body: JSON.stringify({ title, category, deadline, priority }),
        });

        if (response.ok) {
            alert("Agenda berhasil disimpan ke kalender!");
            window.location.reload();
        } else {
            const err = await response.json();
            alert(err.message || "Gagal menyimpan agenda.");
        }
    } catch (error) {
        alert("Terjadi kesalahan jaringan.");
    }
}

/**
 * Tandai Selesai / Belum Selesai Tugas -> Kirim ke MySQL via PATCH /tasks/{id}/toggle
 */
async function toggleTaskComplete(taskId) {
    try {
        const response = await fetch(`/tasks/${taskId}/toggle`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCSRFToken(),
                Accept: "application/json",
            },
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert("Gagal memperbarui status tugas.");
        }
    } catch (error) {
        alert("Terjadi kesalahan jaringan.");
    }
}

/**
 * Hapus Tugas -> Kirim ke MySQL via DELETE /tasks/{id}
 */
async function deleteTask(taskId) {
    if (!confirm("Apakah Anda yakin ingin menghapus agenda ini?")) {
        return;
    }

    try {
        const response = await fetch(`/tasks/${taskId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": getCSRFToken(),
                Accept: "application/json",
            },
        });

        if (response.ok) {
            // Hapus elemen dari DOM jika ada
            const el = document.getElementById(`task-row-${taskId}`) || document.getElementById(`guru-task-row-${taskId}`);
            if (el) el.remove();
            window.location.reload();
        } else {
            alert("Gagal menghapus agenda.");
        }
    } catch (error) {
        alert("Terjadi kesalahan jaringan.");
    }
}

/**
 * FITUR UTAMA: Ambil data tanggal & agenda dari MySQL dan render kalender dinamis
 */
async function fetchAndRenderCalendar(year, month) {
    const calCellsEl = document.getElementById("calendar-cells");
    const monthYearEl = document.getElementById("cal-month-year");
    if (!calCellsEl) return;

    calCellsEl.innerHTML = '<div class="col-span-7 py-8 text-text-muted font-bold">Memuat agenda kalender...</div>';

    try {
        const response = await fetch(`/api/calendar/month?year=${year}&month=${month}`, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!response.ok) throw new Error("Gagal memuat kalender.");

        const data = await response.json();

        // 1. Tampilkan Judul Bulan dan Tahun
        if (monthYearEl) {
            monthYearEl.innerText = `${data.month_name} ${data.year}`;
        }

        calCellsEl.innerHTML = "";

        // 2. Kotak Kosong Padding Awal Bulan (Minggu - Sabtu)
        for (let i = 0; i < data.first_day_of_week; i++) {
            calCellsEl.innerHTML += '<div class="cal-cell bg-transparent border-none shadow-none pointer-events-none"></div>';
        }

        // 3. Mapping Gaya Kategori Agenda
        const pillStyles = {
            sekolah: "bg-cat-sekolah-bg text-cat-sekolah border-red-200",
            pribadi: "bg-cat-pribadi-bg text-cat-pribadi border-emerald-200",
            acara: "bg-cat-acara-bg text-cat-acara border-purple-200",
        };

        // 4. Render Seluruh Tanggal dalam Bulan Tersebut (Mendukung Tahun Kabisat Otomatis via Carbon)
        for (let day = 1; day <= data.days_in_month; day++) {
            const dateStr = `${data.year}-${String(data.month).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
            const isToday = (dateStr === data.today_date);
            const dayAgendas = data.agendas[dateStr] || [];

            let tasksHtml = '<div class="cal-task-list w-full flex flex-col gap-1 overflow-y-auto max-h-[65px] mt-1">';
            dayAgendas.forEach((item) => {
                const style = pillStyles[item.category] || pillStyles.sekolah;
                tasksHtml += `
                    <div class="cal-task-pill text-[0.65rem] lg:text-[0.7rem] px-1.5 py-0.5 rounded-md font-bold text-left whitespace-nowrap overflow-hidden text-ellipsis w-full border ${style}" title="${item.title}">
                        ${item.title}
                    </div>
                `;
            });
            tasksHtml += "</div>";

            const todayClasses = isToday
                ? "border-primary-orange bg-white shadow-[0_0_0_3px_#FFF0ED]"
                : "border-border-custom bg-bg-main hover:border-text-dark hover:bg-white hover:-translate-y-0.5 hover:shadow-pop-3px";

            const dayNumberStyle = isToday
                ? "bg-primary-orange text-white w-6 h-6 rounded-full grid place-items-center font-extrabold"
                : "text-text-dark font-extrabold";

            calCellsEl.innerHTML += `
                <div class="cal-cell min-h-[75px] lg:min-h-[105px] rounded-xl p-1.5 lg:p-2 text-sm font-bold flex flex-col items-start relative border-2 transition-all duration-200 ${todayClasses}">
                    <span class="cal-day-number text-xs mb-1 ${dayNumberStyle}">${day}</span>
                    ${dayAgendas.length > 0 ? tasksHtml : ""}
                </div>
            `;
        }
    } catch (error) {
        calCellsEl.innerHTML = `<div class="col-span-7 py-8 text-red-500 font-bold">${error.message}</div>`;
    }
}

/**
 * Navigasi Kalender: Bulan Lalu (-1) & Bulan Depan (+1)
 */
function navigateCalendar(direction) {
    currentCalMonth += direction;
    if (currentCalMonth > 12) {
        currentCalMonth = 1;
        currentCalYear++;
    } else if (currentCalMonth < 1) {
        currentCalMonth = 12;
        currentCalYear--;
    }
    fetchAndRenderCalendar(currentCalYear, currentCalMonth);
}

/**
 * Navigasi Kalender: Kembali ke Hari Ini
 */
function resetCalendarToToday() {
    const today = new Date();
    currentCalYear = today.getFullYear();
    currentCalMonth = today.getMonth() + 1;
    fetchAndRenderCalendar(currentCalYear, currentCalMonth);
}

// Inisialisasi saat DOM siap
document.addEventListener("DOMContentLoaded", function () {
    // Muat kalender jika elemen view-kalender tersedia
    if (document.getElementById("view-kalender")) {
        fetchAndRenderCalendar(currentCalYear, currentCalMonth);
    }

    // Kelola parameter ?tab=... dari query string
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    const path = window.location.pathname;

    if (tabParam) {
        switchTab(tabParam);
    } else if (
        path.includes("/teacher") ||
        path.includes("/council") ||
        path.includes("/admin")
    ) {
        switchTab("role-panel");
    } else if (path.includes("/students") || path === "/") {
        switchTab("kalender");
    }
});

// Listener saat tombol browser back/forward ditekan
window.addEventListener("popstate", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get("tab");
    const path = window.location.pathname;

    if (tabParam) {
        switchTab(tabParam);
    } else if (
        path.includes("/teacher") ||
        path.includes("/council") ||
        path.includes("/admin")
    ) {
        switchTab("role-panel");
    } else {
        switchTab("kalender");
    }
});

// Export helper ke global window object
window.toggleMobileMenu = toggleMobileMenu;
window.closeMobileMenu = closeMobileMenu;
window.goToRoleHome = goToRoleHome;
window.switchTab = switchTab;
window.handleAddTask = handleAddTask;
window.toggleTaskComplete = toggleTaskComplete;
window.deleteTask = deleteTask;
window.fetchAndRenderCalendar = fetchAndRenderCalendar;
window.navigateCalendar = navigateCalendar;
window.resetCalendarToToday = resetCalendarToToday;
