<?php
// app/views/components/topbar.php
$currentUser = user();
$initials = 'A';
if ($currentUser && !empty($currentUser['name'])) {
    $parts = preg_split('/\s+/', trim($currentUser['name']));
    $initials = strtoupper(substr($parts[0] ?? 'A', 0, 1) . substr($parts[1] ?? '', 0, 1));
}
?>
<div class="topbar">
    <div class="topbar-left">
        <!-- Sidebar Toggle Collapse / Expand Button -->
        <button type="button" class="topbar-sidebar-toggle-btn" id="sidebarToggleBtn" onclick="window.toggleSidebarCollapse()" title="Ciutkan / Buka Lebar Sidebar (Ctrl+B)" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <!-- Brand Squircle Logo -->
        <a href="<?= url('dashboard') ?>" class="brand-squircle-link" title="<?= e(config_app('name')) ?>">
            <div class="brand-squircle">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </a>

        <span class="topbar-divider-slash">/</span>

        <!-- User / Account Pill Profile Dropdown -->
        <div class="user-pill-dropdown-wrapper" id="userPillWrapper">
            <button type="button" class="user-pill-btn" id="userPillBtn" onclick="window.toggleUserDropdown(event)" title="Informasi Akun" aria-label="Akun Pengguna">
                <span class="user-avatar-circle">
                    <?= e($initials) ?>
                </span>
                <span class="user-email-text">
                    <?= e($currentUser['name'] ?? 'Administrator') ?>
                </span>
                <span class="user-plan-badge">
                    <?= e(ucfirst(str_replace('_', ' ', $currentUser['role'] ?? 'Admin'))) ?>
                </span>
                <i class="fa-solid fa-chevron-down pill-chevron"></i>
            </button>
            <div class="user-pill-menu" id="userPillMenu" style="display: none;">
                <div class="user-pill-menu-header">
                    <div style="font-weight: 600; color: var(--text-main); font-size: 0.88rem;">
                        <?= e($currentUser['name'] ?? 'Administrator') ?>
                    </div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">
                        <?= e($currentUser['email'] ?? 'admin@kampus.test') ?>
                    </div>
                </div>
                <div class="user-pill-menu-divider"></div>
                <a href="<?= url('dashboard') ?>" class="user-pill-menu-item">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>
                <a href="<?= e(url('profil')) ?>" class="user-pill-menu-item">
                    <i class="fa-solid fa-id-card" aria-hidden="true"></i> Profil Saya
                </a>
                <a href="<?= url('jadwal') ?>" class="user-pill-menu-item">
                    <i class="fa-solid fa-calendar-days"></i> Kelola Jadwal
                </a>
                <?php if (has_role(['admin', 'operator_prodi'])): ?>
                <a href="<?= url('ruang') ?>" class="user-pill-menu-item">
                    <i class="fa-solid fa-door-open"></i> Master Ruang
                </a>
                <?php endif; ?>
                <div class="user-pill-menu-divider"></div>
                <a href="<?= url('logout') ?>" class="user-pill-menu-item danger">
                    <i class="fa-solid fa-sign-out-alt"></i> Keluar (Logout)
                </a>
            </div>
        </div>

        <!-- Live Clock Pill (Preserving app.js data attributes) -->
        <div class="dashboard-clock d-none d-md-inline-flex" data-live-clock style="display: inline-flex; align-items: center; gap: 8px; padding: 4px 12px; border: 1px solid var(--border); border-radius: 20px; background: var(--surface); color: var(--text-muted); font-size: 0.8rem; margin-left: 6px;">
            <i class="fa-regular fa-clock" style="color: var(--primary);"></i>
            <span data-live-date style="color: var(--text-muted);"><?= e(date('l, d M Y')) ?></span>
            <strong data-live-time style="color: var(--text-main); font-weight: 700;"><?= e(date('H:i:s')) ?></strong>
        </div>
    </div>

    <div class="topbar-right" style="display: flex; align-items: center; gap: 12px;">
        <!-- Accent Palette Switcher Dropdown -->
        <div class="palette-dropdown-wrapper" id="paletteDropdownWrapper">
            <button type="button" id="paletteToggleBtn" class="palette-toggle-btn" onclick="window.togglePaletteDropdown(event)" title="Pilih Tema Palet Warna" aria-label="Palette Selector">
                <span class="palette-dot-preview" id="paletteActiveDot" style="background-color: #3368A0;"></span>
                <i class="fa-solid fa-palette" style="font-size: 0.85rem; opacity: 0.85;"></i>
                <span id="paletteActiveName" class="d-none d-sm-inline" style="font-size: 0.82rem; font-weight: 600;">Slate Blue</span>
                <i class="fa-solid fa-chevron-down" style="font-size: 0.65rem; opacity: 0.6; margin-left: 2px;"></i>
            </button>
            <div id="paletteDropdownMenu" class="palette-dropdown-menu" style="display: none;">
                <div class="palette-dropdown-header">Warna Aksen</div>
                <div class="palette-options-list">
                    <button type="button" class="palette-option active" data-pal="slate" onclick="window.applyPalette('slate')">
                        <span class="palette-swatch" style="background: #0284c7;"></span>
                        <span class="palette-name">Slate Blue (Utama)</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                    <button type="button" class="palette-option" data-pal="indigo" onclick="window.applyPalette('indigo')">
                        <span class="palette-swatch" style="background: #6366f1;"></span>
                        <span class="palette-name">Indigo</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                    <button type="button" class="palette-option" data-pal="emerald" onclick="window.applyPalette('emerald')">
                        <span class="palette-swatch" style="background: #10b981;"></span>
                        <span class="palette-name">Emerald</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                    <button type="button" class="palette-option" data-pal="ocean" onclick="window.applyPalette('ocean')">
                        <span class="palette-swatch" style="background: #0ea5e9;"></span>
                        <span class="palette-name">Ocean Blue</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                    <button type="button" class="palette-option" data-pal="violet" onclick="window.applyPalette('violet')">
                        <span class="palette-swatch" style="background: #8b5cf6;"></span>
                        <span class="palette-name">Violet</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                    <button type="button" class="palette-option" data-pal="amber" onclick="window.applyPalette('amber')">
                        <span class="palette-swatch" style="background: #f59e0b;"></span>
                        <span class="palette-name">Amber</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                    <button type="button" class="palette-option" data-pal="rose" onclick="window.applyPalette('rose')">
                        <span class="palette-swatch" style="background: #f43f5e;"></span>
                        <span class="palette-name">Rose</span>
                        <i class="fa-solid fa-check palette-check"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Dark / Light Mode Toggle Button -->
        <button type="button" id="themeToggleBtn" class="theme-toggle-btn" onclick="window.toggleTheme()" title="Ganti Mode Gelap / Terang (Dark / Light Mode)" aria-label="Toggle Theme">
            <span class="theme-toggle-icon">
                <i class="fa-solid fa-moon" id="themeToggleIcon"></i>
            </span>
            <span id="themeToggleText" style="font-size: 0.82rem; letter-spacing: 0.3px;">Dark</span>
        </button>
    </div>
</div>
