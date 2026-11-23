<div class="public-shell">
    <!-- Public Navbar Header -->
    <nav class="public-navbar">
        <a class="public-brand" href="<?= url('') ?>">
            <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: linear-gradient(135deg, var(--primary) 0%, #818cf8 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(var(--primary-rgb), 0.35); flex-shrink: 0;">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <span class="public-brand-title"><?= e(config_app('name')) ?></span>
                <span class="public-brand-sub">Portal Informasi Perkuliahan</span>
            </div>
        </a>

        <div class="public-nav-actions">
            <!-- Theme Toggle Button -->
            <button type="button" class="btn btn-secondary" onclick="toggleThemeGlobal()" style="padding: 7px 14px; font-size: 0.85rem;" title="Ubah Tema Gelap / Terang">
                <i id="publicThemeIcon" class="fa-solid fa-moon mr-1"></i>
                <span id="publicThemeText">Dark</span>
            </button>

            <?php if (user()): ?>
                <a href="<?= url('dashboard') ?>" class="btn btn-primary" style="box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.25);">
                    <i class="fa-solid fa-gauge mr-1"></i> Buka Dashboard
                </a>
                <a href="<?= url('logout') ?>" class="btn btn-secondary" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            <?php else: ?>
                <a href="<?= url('login') ?>" class="btn btn-primary" style="box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.25);">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Masuk Sistem
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="public-content">
        <!-- Hero Section Header -->
        <section class="public-heading">
            <div class="public-badge-pill">
                <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--primary);"></span>
                Monitoring Ruang Terpadu
            </div>
            <h1 class="public-hero-title">
                Penjadwalan Ruang Perkuliahan
            </h1>
            <p class="public-hero-desc">
                Informasi jadwal penggunaan ruang kuliah, plotting kelas rombel, dan dosen pengampu secara transparan dan real-time.
            </p>
        </section>

        <!-- KPI Summary Cards Grid -->
        <section class="summary-display-grid">
            <?php foreach ($cards as $card): ?>
                <div class="summary-stat-card">
                    <div>
                        <span class="summary-stat-label">
                            <?= e($card['label']) ?>
                        </span>
                        <strong class="summary-stat-val">
                            <?= e($card['value']) ?>
                        </strong>
                    </div>
                    <div class="summary-stat-icon">
                        <i class="fa-solid fa-<?= e($card['icon']) ?>"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Rooms Live Grid Section -->
        <section class="public-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h2 style="font-size: 1.35rem; font-weight: 700; color: var(--text-main); margin: 0; letter-spacing: -0.02em;">
                        Status Ruang Kuliah & Agenda Hari Ini
                    </h2>
                    <p style="font-size: 0.86rem; color: var(--text-muted); margin: 4px 0 0;">
                        Pantau sesi perkuliahan aktif dan jadwal penggunaan ruang secara langsung.
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: var(--text-muted);">
                    <i class="fa-solid fa-circle-dot text-success" style="font-size: 0.65rem;"></i>
                    <span>Pembaruan Otomatis Terhubung</span>
                </div>
            </div>

            <div class="room-display-grid">
                <?php foreach ($rooms as $room): ?>
                    <article class="room-preview-card">
                        <div class="room-card-head">
                            <div>
                                <h3 class="room-card-title">
                                    <?= e($room['nama_ruang']) ?>
                                </h3>
                                <div class="room-card-sub">
                                    <span style="font-family: monospace; font-weight: 600; color: var(--primary);"><?= e($room['kode_ruang']) ?></span>
                                    &bull; <span><?= e($room['lokasi'] ?: 'Gedung Utama') ?></span>
                                </div>
                            </div>
                            <span style="background: rgba(var(--primary-rgb), 0.12); border: 1px solid rgba(var(--primary-rgb), 0.25); color: var(--primary); padding: 3px 8px; border-radius: 9999px; font-size: 0.74rem; font-weight: 700;">
                                <?= e($room['kapasitas']) ?> kursi
                            </span>
                        </div>

                        <div class="room-card-body">
                            <?php if (!empty($room['jadwal'])): ?>
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <?php foreach (array_slice($room['jadwal'], 0, 2) as $schedule): ?>
                                        <div class="room-schedule-item">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                                <span class="room-schedule-time">
                                                    <?= e($schedule['hari']) ?>, <?= e(time_short($schedule['jam_mulai'])) ?> - <?= e(time_short($schedule['jam_selesai'])) ?>
                                                </span>
                                                <span class="room-schedule-badge">
                                                    <?= e($schedule['kode_kelas']) ?>
                                                </span>
                                            </div>
                                            <div class="room-schedule-title">
                                                <?= e($schedule['mata_kuliah']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="room-running-box">
                                    <div class="room-running-text">
                                        <?php for ($loop = 0; $loop < 2; $loop++): ?>
                                            <span>
                                                <?php foreach ($room['jadwal'] as $schedule): ?>
                                                    <i class="fa-solid fa-clock mr-1" style="color: var(--primary);"></i> <?= e($schedule['hari']) ?> (<?= e(time_short($schedule['jam_mulai'])) ?>-<?= e(time_short($schedule['jam_selesai'])) ?>) [<?= e($schedule['kode_kelas']) ?>: <?= e($schedule['mata_kuliah']) ?>] &nbsp;&bull;&nbsp;
                                                <?php endforeach; ?>
                                            </span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div style="text-align: center; padding: 24px 12px; color: var(--text-muted);">
                                    <i class="fa-regular fa-calendar-check" style="font-size: 1.8rem; opacity: 0.35; margin-bottom: 8px; display: block;"></i>
                                    <span style="font-size: 0.85rem; font-weight: 500;">Tidak ada perkuliahan aktif hari ini</span>
                                </div>
                                <div class="room-running-box">
                                    <div class="room-running-text">
                                        <span>Ruang <?= e($room['nama_ruang']) ?> tersedia untuk perkuliahan / kegiatan kemahasiswaan &nbsp;&bull;&nbsp;</span>
                                        <span>Ruang <?= e($room['nama_ruang']) ?> tersedia untuk perkuliahan / kegiatan kemahasiswaan &nbsp;&bull;&nbsp;</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</div>

<!-- Global Theme Script for Public Portal -->
<script>
function syncPublicThemeUI(theme) {
    const icon = document.getElementById('publicThemeIcon');
    const text = document.getElementById('publicThemeText');
    if (icon && text) {
        if (theme === 'light') {
            icon.className = 'fa-solid fa-sun mr-1 text-warning';
            text.textContent = 'Light';
        } else {
            icon.className = 'fa-solid fa-moon mr-1';
            text.textContent = 'Dark';
        }
    }
}

function toggleThemeGlobal() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', newTheme);
    document.documentElement.classList.remove('dark', 'light');
    document.documentElement.classList.add(newTheme);
    localStorage.setItem('theme', newTheme);
    syncPublicThemeUI(newTheme);
}

document.addEventListener('DOMContentLoaded', function() {
    const theme = localStorage.getItem('theme') || 'dark';
    syncPublicThemeUI(theme);
});
</script>
