<?php
// app/views/components/sidebar.php
$isMasterActive = active_menu('ruang')
    || active_menu('prodi')
    || active_menu('kelas')
    || active_menu('mata-kuliah')
    || active_menu('dosen')
    || active_menu('jam');

$isJadwalActive = active_menu('jadwal');
?>
<aside class="sidebar" id="appSidebar">
    <!-- Search Bar with '/' Shortcut -->
    <div class="sidebar-search-box">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="sidebarSearchInput" placeholder="Cari menu... (Search)" autocomplete="off" spellcheck="false">
        <span class="sidebar-kbd" title="Tekan '/' untuk mencari">/</span>
    </div>

    <!-- Info Pill: Semester / Sistem Berjalan (Responsive) -->
    <div class="sidebar-branch-pill-wrapper" id="branchPillWrapper">
        <div class="sidebar-branch-pill" title="Sistem Penjadwalan Ruang Kuliah - Semester Aktif 2025/2026">
            <span class="branch-pill-left">
                <i class="fa-solid fa-calendar-check"></i>
                <span id="activeSemesterText">TA 2025/2026</span>
            </span>
            <span class="branch-pill-badge">
                <span class="badge-dot"></span>
                <span>Aktif</span>
            </span>
        </div>
    </div>

    <!-- Quick Action: Toggle Collapse All Categories -->
    <div class="sidebar-action-bar">
        <button type="button" class="sidebar-mini-action" id="toggleAllGroupsBtn" onclick="window.toggleAllSidebarGroups()" title="Ciutkan atau bentangkan semua kelompok menu">
            <i class="fa-solid fa-arrows-up-down"></i>
            <span id="toggleAllGroupsText">Ciutkan Semua</span>
        </button>
    </div>

    <!-- Scrollable Navigation Body -->
    <div class="sidebar-scrollable" id="sidebarScrollable">
        <!-- Section: Menu Utama (Selalu Terbuka) -->
        <div class="sidebar-group always-open expanded" data-group="main">
            <div class="sidebar-section-title">
                <i class="fa-solid fa-compass group-icon"></i>
                <span>MENU UTAMA</span>
            </div>
            <div class="sidebar-group-content">
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item <?= active_menu('dashboard') ?>" data-label="dashboard beranda overview ringkasan statistik home">
                        <a href="<?= url('dashboard') ?>" class="sidebar-link <?= active_menu('dashboard') ?>" title="Dashboard">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php if (has_role(['admin', 'operator_prodi'])): ?>
        <!-- Section: Master Data (Collapsible Accordion) -->
        <div class="sidebar-group <?= $isMasterActive ? 'expanded' : '' ?>" data-group="master">
            <button type="button" class="sidebar-group-header" onclick="window.toggleSidebarGroup('master')" title="Klik untuk ciutkan/buka Master Data">
                <span class="sidebar-section-title">
                    <i class="fa-solid fa-database group-icon"></i>
                    <span>MASTER DATA</span>
                </span>
                <span class="sidebar-group-meta">
                    <span class="sidebar-group-badge">6</span>
                    <i class="fa-solid fa-chevron-down group-chevron"></i>
                </span>
            </button>
            <div class="sidebar-group-content">
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item <?= active_menu('ruang') ?>" data-label="ruang kelas gedung laboratorium kapasitas fasilitas room">
                        <a href="<?= url('ruang') ?>" class="sidebar-link <?= active_menu('ruang') ?>" title="Data Ruang">
                            <i class="fa-solid fa-door-open"></i>
                            <span>Data Ruang</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('prodi') ?>" data-label="program studi prodi jurusan fakultas department">
                        <a href="<?= url('prodi') ?>" class="sidebar-link <?= active_menu('prodi') ?>" title="Program Studi">
                            <i class="fa-solid fa-building-columns"></i>
                            <span>Program Studi</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('kelas') ?>" data-label="kelas angkatan rombel mahasiswa semester class">
                        <a href="<?= url('kelas') ?>" class="sidebar-link <?= active_menu('kelas') ?>" title="Data Kelas">
                            <i class="fa-solid fa-users"></i>
                            <span>Data Kelas</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('mata-kuliah') ?>" data-label="mata kuliah matkul sks kurikulum courses">
                        <a href="<?= url('mata-kuliah') ?>" class="sidebar-link <?= active_menu('mata-kuliah') ?>" title="Mata Kuliah">
                            <i class="fa-solid fa-book"></i>
                            <span>Mata Kuliah</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('dosen') ?>" data-label="dosen pengajar nidn lecturer faculty guru">
                        <a href="<?= url('dosen') ?>" class="sidebar-link <?= active_menu('dosen') ?>" title="Data Dosen">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span>Data Dosen</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('jam') ?>" data-label="jam slot waktu sesi perkuliahan time slot periode">
                        <a href="<?= url('jam') ?>" class="sidebar-link <?= active_menu('jam') ?>" title="Jam Perkuliahan">
                            <i class="fa-solid fa-clock"></i>
                            <span>Jam Perkuliahan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <!-- Section: Penjadwalan & Cetak -->
        <div class="sidebar-group <?= $isJadwalActive ? 'expanded' : '' ?>" data-group="jadwal">
            <button type="button" class="sidebar-group-header" onclick="window.toggleSidebarGroup('jadwal')" title="Klik untuk ciutkan/buka Jadwal">
                <span class="sidebar-section-title">
                    <i class="fa-solid fa-calendar-alt group-icon"></i>
                    <span>PENJADWALAN</span>
                </span>
                <span class="sidebar-group-meta">
                    <span class="sidebar-group-badge">3</span>
                    <i class="fa-solid fa-chevron-down group-chevron"></i>
                </span>
            </button>
            <div class="sidebar-group-content">
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item <?= (active_menu('jadwal/cetak') || active_menu('jadwal/cetak-dosen')) ? '' : active_menu('jadwal') ?>" data-label="kelola jadwal kuliah buat bentrok slot schedule matrix">
                        <a href="<?= url('jadwal') ?>" class="sidebar-link <?= (active_menu('jadwal/cetak') || active_menu('jadwal/cetak-dosen')) ? '' : active_menu('jadwal') ?>" title="Kelola Jadwal">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Kelola Jadwal</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('jadwal/cetak-dosen') ? '' : active_menu('jadwal/cetak') ?>" data-label="cetak jadwal kelas matriks mingguan print report pdf">
                        <a href="<?= url('jadwal/cetak') ?>" class="sidebar-link <?= active_menu('jadwal/cetak-dosen') ? '' : active_menu('jadwal/cetak') ?>" title="Cetak Jadwal Kelas">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Jadwal Kelas</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item <?= active_menu('jadwal/cetak-dosen') ?>" data-label="cetak jadwal dosen mengajar sks pengampu print report">
                        <a href="<?= url('jadwal/cetak-dosen') ?>" class="sidebar-link <?= active_menu('jadwal/cetak-dosen') ?>" title="Cetak Jadwal Dosen">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span>Cetak Jadwal Dosen</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Section: Portal Eksternal / Beranda -->
        <div class="sidebar-group always-open expanded" data-group="portal">
            <div class="sidebar-section-title">
                <i class="fa-solid fa-link group-icon"></i>
                <span>PORTAL PUBLIK</span>
            </div>
            <div class="sidebar-group-content">
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item" data-label="portal publik matriks beranda umum website">
                        <a href="<?= url('') ?>" target="_blank" class="sidebar-link" title="Buka Portal Jadwal Publik">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            <span>Lihat Matriks Publik</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sidebar Bottom Footer: Trigger Ciutkan Lebar Sidebar -->
    <div class="sidebar-footer">
        <button type="button" class="sidebar-footer-collapse-btn" id="sidebarFooterCollapseBtn" onclick="window.toggleSidebarCollapse()" title="Ciutkan / Buka Lebar Sidebar (Ctrl+B)">
            <i class="fa-solid fa-angles-left collapse-icon" id="sidebarFooterIcon"></i>
            <span class="collapse-label" id="sidebarFooterLabel">Ciutkan Sidebar</span>
            <kbd class="sidebar-kbd collapse-label">Ctrl+B</kbd>
        </button>
    </div>
</aside>

<script>
(function() {
    // 1. Sidebar Search Filter
    const searchInput = document.getElementById('sidebarSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const navItems = document.querySelectorAll('.sidebar-nav-item');
            const groups = document.querySelectorAll('.sidebar-group:not(.always-open)');

            if (query === '') {
                navItems.forEach(item => { item.style.display = ''; });
                return;
            }

            // Buka semua group saat sedang mencari
            groups.forEach(group => { group.classList.add('expanded'); });

            navItems.forEach(item => {
                const label = (item.getAttribute('data-label') || '').toLowerCase();
                const text = item.textContent.toLowerCase();
                if (label.includes(query) || text.includes(query)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Shortcut keyboard '/' untuk fokus ke search
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement !== searchInput && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }

    // 2. Toggle Per Group Accordion
    window.toggleSidebarGroup = function(groupId) {
        const group = document.querySelector(`.sidebar-group[data-group="${groupId}"]`);
        if (group && !group.classList.contains('always-open')) {
            group.classList.toggle('expanded');
        }
    };

    // 3. Toggle All Groups
    let allExpanded = true;
    window.toggleAllSidebarGroups = function() {
        const groups = document.querySelectorAll('.sidebar-group:not(.always-open)');
        const btnText = document.getElementById('toggleAllGroupsText');
        allExpanded = !allExpanded;

        groups.forEach(g => {
            if (allExpanded) {
                g.classList.add('expanded');
            } else {
                g.classList.remove('expanded');
            }
        });

        if (btnText) {
            btnText.textContent = allExpanded ? 'Ciutkan Semua' : 'Bentangkan Semua';
        }
    };

    // 4. Toggle Sidebar Collapse (Ctrl + B)
    window.toggleSidebarCollapse = function() {
        const html = document.documentElement;
        const isCollapsed = html.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar_collapsed', isCollapsed ? 'true' : 'false');
        updateSidebarCollapseUI(isCollapsed);
    };

    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
            e.preventDefault();
            window.toggleSidebarCollapse();
        }
    });

    function updateSidebarCollapseUI(collapsed) {
        const footerIcon = document.getElementById('sidebarFooterIcon');
        const footerLabel = document.getElementById('sidebarFooterLabel');
        const topbarBtn = document.getElementById('sidebarToggleBtn');

        if (footerIcon) {
            footerIcon.className = collapsed ? 'fa-solid fa-angles-right collapse-icon' : 'fa-solid fa-angles-left collapse-icon';
        }
        if (footerLabel) {
            footerLabel.textContent = collapsed ? 'Lebarkan' : 'Ciutkan Sidebar';
        }
        if (topbarBtn) {
            topbarBtn.setAttribute('title', collapsed ? 'Buka Lebar Sidebar (Ctrl+B)' : 'Ciutkan Sidebar (Ctrl+B)');
        }
    }

    const initCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
    updateSidebarCollapseUI(initCollapsed);

    // 5. User Pill Dropdown Handler
    window.toggleUserDropdown = function(e) {
        if (e) e.stopPropagation();
        const palMenu = document.getElementById('paletteDropdownMenu');
        if (palMenu) palMenu.style.display = 'none';

        const menu = document.getElementById('userPillMenu');
        if (menu) {
            menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
        }
    };

    // Tutup dropdown jika user klik di luar area
    document.addEventListener('click', function(e) {
        const userWrapper = document.getElementById('userPillWrapper');
        const userMenu = document.getElementById('userPillMenu');
        if (userWrapper && userMenu && !userWrapper.contains(e.target)) {
            userMenu.style.display = 'none';
        }
    });
})();
</script>
