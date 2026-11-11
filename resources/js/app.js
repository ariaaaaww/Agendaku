/* =========================================================================
 * AGENDAKU - CLIENT UI SCRIPT (TAB SWITCHING & MOBILE NAVIGATION)
 * ========================================================================= */

/**
 * 1. Navigasi Menu Mobile (Buka & Tutup Sidebar di Layar HP)
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
 * 2. Logika Perpindahan Tab (Kalender, To-Do List, Acara Sekolah, Tambah Agenda, Panel Role)
 */
function switchTab(tabName) {
    const tabs = ["kalender", "tugas", "acara", "tambah", "role-panel"];

    // Sembunyikan semua tab & hapus status tombol aktif
    tabs.forEach((t) => {
        const el = document.getElementById("view-" + t);
        const btn = document.getElementById(
            t === "role-panel" ? "tab-btn-role" : "tab-btn-" + t,
        );
        if (el) el.classList.add("hidden");
        if (btn) btn.classList.remove("active");
    });

    // Sembunyikan alert banner jika membuka tab 'tambah' atau 'role-panel'
    const alertBanner = document.getElementById("alert-banner-container");
    if (alertBanner) {
        if (tabName === "tambah" || tabName === "role-panel") {
            alertBanner.classList.add("hidden");
        } else {
            alertBanner.classList.remove("hidden");
        }
    }

    // Tampilkan tab yang dipilih & beri style aktif pada tombolnya
    const targetView = document.getElementById("view-" + tabName);
    const targetBtn = document.getElementById(
        tabName === "role-panel" ? "tab-btn-role" : "tab-btn-" + tabName,
    );
    if (targetView) targetView.classList.remove("hidden");
    if (targetBtn) targetBtn.classList.add("active");

    // Perbarui URL browser tanpa reload (misal: /students?tab=tugas)
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
 * 3. Inisialisasi Tab Saat Halaman Selesai Dimuat
 */
document.addEventListener("DOMContentLoaded", function () {
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

/**
 * 4. Mendukung Tombol Back & Forward Browser
 */
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

/**
 * 5. Ekspor Fungsi ke Window agar Bisa Dipanggil dari Atribut onclick="..." di Blade
 */
window.toggleMobileMenu = toggleMobileMenu;
window.closeMobileMenu = closeMobileMenu;
window.goToRoleHome = goToRoleHome;
window.switchTab = switchTab;