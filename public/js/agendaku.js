const defaultUsers = [
  { id: 1, fullname: "Budi Santoso", username: "siswa", password: "123", role: "siswa" },
  { id: 2, fullname: "Administrator", username: "admin", password: "123", role: "admin" },
  { id: 3, fullname: "Pak Guruh, M.Pd", username: "guru", password: "123", role: "guru" },
  { id: 4, fullname: "Pengurus OSIS", username: "osis", password: "123", role: "osis" }
];

function getTodayDateString() {
  const d = new Date();
  return d.toISOString().split('T')[0];
}

function getTomorrowDateString() {
  const d = new Date();
  d.setDate(d.getDate() + 1);
  return d.toISOString().split('T')[0];
}

const defaultTasks = [
  { id: 101, userId: 1, title: "Tugas Matematika Bab 3", category: "sekolah", deadline: getTomorrowDateString(), priority: "tinggi", completed: false, createdBy: "Siswa" },
  { id: 102, userId: 1, title: "Latihan Futsal", category: "pribadi", deadline: getTodayDateString(), priority: "sedang", completed: true, createdBy: "Siswa" },
  { id: 103, userId: 0, title: "Classmeet Futsal & Seni", category: "acara", deadline: getTomorrowDateString(), priority: "sedang", completed: false, createdBy: "OSIS" }
];

// Inisialisasi awal ke LocalStorage
let storedUsers = JSON.parse(localStorage.getItem('users'));
if (!storedUsers || storedUsers.length < 4) {
  localStorage.setItem('users', JSON.stringify(defaultUsers));
}

if (!localStorage.getItem('tasks')) localStorage.setItem('tasks', JSON.stringify(defaultTasks));
if (!localStorage.getItem('announcement')) localStorage.setItem('announcement', 'Ujian Tengah Semester dimulai tanggal 25 mendatang. Diharapkan siswa mempersiapkan diri!');

let currentUser = JSON.parse(sessionStorage.getItem('currentUser')) || null;

// Auto initialize currentUser based on active route if not logged in
if (!currentUser) {
  const path = window.location.pathname;
  if (path.includes('/teachers')) {
    currentUser = defaultUsers[2]; // Guru
  } else if (path.includes('/admin')) {
    currentUser = defaultUsers[1]; // Admin
  } else if (path.includes('/council')) {
    currentUser = defaultUsers[3]; // OSIS
  } else if (path.includes('/students') || path === '/') {
    currentUser = defaultUsers[0]; // Siswa
  }
  if (currentUser) {
    sessionStorage.setItem('currentUser', JSON.stringify(currentUser));
  }
}

/* --- MOBILE MENU TOGGLE --- */
function toggleMobileMenu() {
  const overlay = document.getElementById('sidebar-overlay');
  const navTabs = document.getElementById('app-tab-nav');
  const navUserInfo = document.getElementById('nav-user-info');

  if (overlay) overlay.classList.toggle('hidden');
  if (navTabs) navTabs.classList.toggle('max-lg:!left-0');
  if (navUserInfo) navUserInfo.classList.toggle('max-lg:!left-0');
}

function closeMobileMenu() {
  const overlay = document.getElementById('sidebar-overlay');
  const navTabs = document.getElementById('app-tab-nav');
  const navUserInfo = document.getElementById('nav-user-info');

  if (overlay) overlay.classList.add('hidden');
  if (navTabs) navTabs.classList.remove('max-lg:!left-0');
  if (navUserInfo) navUserInfo.classList.remove('max-lg:!left-0');
}

/* --- HEADER & NAVIGATION UPDATE --- */
function updateNavHeader() {
  const nav = document.getElementById('nav-user-info');
  const tabNav = document.getElementById('app-tab-nav');
  const roleBtn = document.getElementById('tab-btn-role');

  if (currentUser) {
    if (nav) {
      nav.classList.remove('hidden');
      nav.classList.add('flex');
    }
    if (tabNav) {
      tabNav.classList.remove('hidden');
      tabNav.classList.add('flex');
    }
    const nameEl = document.getElementById('user-display-name');
    if (nameEl) nameEl.innerText = currentUser.fullname;

    const avatarEl = document.getElementById('user-avatar-initial');
    if (avatarEl) avatarEl.innerText = currentUser.fullname.charAt(0).toUpperCase();

    const roleBadge = document.getElementById('user-role-badge');
    if (roleBadge) {
      roleBadge.innerText = currentUser.role;
      const roleColors = {
        siswa: 'bg-primary-light text-primary-orange border-primary-orange/30',
        admin: 'bg-amber-100 text-amber-700 border-amber-500/30',
        guru: 'bg-green-100 text-green-700 border-green-600/30',
        osis: 'bg-purple-100 text-purple-700 border-purple-600/30'
      };
      roleBadge.className = `badge-role inline-block px-2.5 py-0.5 rounded-full text-[0.7rem] font-extrabold uppercase tracking-wider w-fit mt-0.5 border ${roleColors[currentUser.role] || roleColors.siswa}`;
    }

    if (roleBtn) {
      if (currentUser.role !== 'siswa') {
        roleBtn.classList.remove('hidden');
        roleBtn.classList.add('flex');
        const roleRoutes = {
          admin: { label: '<span>⚙️</span> Panel Admin', url: '/admin' },
          guru: { label: '<span>👨‍🏫</span> Panel Guru', url: '/teachers' },
          osis: { label: '<span>🌟</span> Panel OSIS', url: '/council' }
        };
        const config = roleRoutes[currentUser.role];
        if (config) {
          roleBtn.innerHTML = config.label;
          roleBtn.onclick = function() {
            window.location.href = config.url;
          };
        }
      } else {
        roleBtn.classList.add('hidden');
      }
    }

    const path = window.location.pathname;
    if (path.includes('/teachers') || path.includes('/admin') || path.includes('/council')) {
      const tabs = ['kalender', 'tugas', 'acara', 'tambah'];
      tabs.forEach(t => {
        const btn = document.getElementById('tab-btn-' + t);
        if (btn) btn.classList.remove('active');
      });
      if (roleBtn) roleBtn.classList.add('active');
    }
  } else {
    if (nav) nav.classList.add('hidden');
    if (tabNav) tabNav.classList.add('hidden');
  }
}

/* --- TAB SWITCHING LOGIC --- */
function switchTab(tabName) {
  if (!document.getElementById('view-kalender')) {
    window.location.href = '/students?tab=' + tabName;
    return;
  }

  const tabs = ['kalender', 'tugas', 'acara', 'tambah', 'role-panel'];
  tabs.forEach(t => {
    const el = document.getElementById('view-' + t);
    const btn = document.getElementById('tab-btn-' + t);
    if (el) el.classList.add('hidden');
    if (btn) btn.classList.remove('active');
  });

  const alertBanner = document.getElementById('alert-banner-container');
  if (alertBanner) {
    if (tabName === 'tambah') {
      alertBanner.classList.add('hidden');
    } else {
      alertBanner.classList.remove('hidden');
    }
  }

  const targetView = document.getElementById('view-' + tabName);
  const targetBtn = document.getElementById('tab-btn-' + tabName);
  if (targetView) targetView.classList.remove('hidden');
  if (targetBtn) targetBtn.classList.add('active');

  closeMobileMenu();
}

/* --- AUTHENTICATION HANDLERS --- */
function handleLogin(e) {
  e.preventDefault();
  const u = document.getElementById('login-username').value.trim();
  const p = document.getElementById('login-password').value.trim();

  const users = JSON.parse(localStorage.getItem('users'));
  const found = users.find(user => user.username.toLowerCase() === u.toLowerCase() && user.password === p);

  if (found) {
    currentUser = found;
    sessionStorage.setItem('currentUser', JSON.stringify(currentUser));
    if (currentUser.role === 'admin') {
      window.location.href = '/admin';
    } else if (currentUser.role === 'guru') {
      window.location.href = '/teachers';
    } else if (currentUser.role === 'osis') {
      window.location.href = '/council';
    } else {
      window.location.href = '/students';
    }
  } else {
    alert("Username atau Password salah! Pastikan menggunakan password '123'");
  }
}

function handleSignup(e) {
  e.preventDefault();
  const fullname = document.getElementById('signup-fullname').value;
  const username = document.getElementById('signup-username').value.trim();
  const role = document.getElementById('signup-role').value;
  const password = document.getElementById('signup-password').value;

  const users = JSON.parse(localStorage.getItem('users'));
  if (users.some(u => u.username.toLowerCase() === username.toLowerCase())) {
    alert("Username telah dipakai!");
    return;
  }

  const newUser = { id: Date.now(), fullname, username, password, role };
  users.push(newUser);
  localStorage.setItem('users', JSON.stringify(users));

  alert("Pendaftaran berhasil, silakan masuk.");
  window.location.href = '/login';
}

function logout() {
  currentUser = null;
  sessionStorage.removeItem('currentUser');
  window.location.href = '/login';
}

function deleteTask(taskId) {
  if (confirm("Apakah Anda yakin ingin menghapus agenda ini?")) {
    let tasks = JSON.parse(localStorage.getItem('tasks'));
    tasks = tasks.filter(t => t.id !== taskId);
    localStorage.setItem('tasks', JSON.stringify(tasks));

    if (typeof renderDashboard === 'function') renderDashboard();
    if (typeof renderGuruData === 'function') renderGuruData();
    if (typeof renderAdminData === 'function') renderAdminData();
    if (typeof renderOsisData === 'function') renderOsisData();
  }
}

function toggleTaskComplete(taskId) {
  let tasks = JSON.parse(localStorage.getItem('tasks'));
  tasks = tasks.map(t => {
    if (t.id === taskId) t.completed = !t.completed;
    return t;
  });
  localStorage.setItem('tasks', JSON.stringify(tasks));
  if (typeof renderDashboard === 'function') renderDashboard();
}

document.addEventListener('DOMContentLoaded', function() {
  updateNavHeader();
});
