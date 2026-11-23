<?php
$flashSuccess = flash('success');
$flashError = flash('error');
$currentUser = user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config_app('name')) ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr & SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= url('assets/css/app.css?v=20261004-obe-v10') ?>">

    <!-- Anti-FOUC Script: Eksekusi tema & palet sebelum DOM selesai render -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme);
                document.documentElement.classList.remove('dark', 'light');
                document.documentElement.classList.add(savedTheme);

                const savedPalette = localStorage.getItem('accent_palette') || 'slate';
                document.documentElement.setAttribute('data-palette', savedPalette);

                if (localStorage.getItem('sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body>
    <div class="app-layout" id="appLayout">
        <!-- Topbar Header -->
        <?php require __DIR__ . '/../components/topbar.php'; ?>

        <div class="app-container">
            <!-- Floating Sidebar Island -->
            <?php require __DIR__ . '/../components/sidebar.php'; ?>

            <!-- Main Content Area -->
            <div class="main-content">
                <div class="content-area">
                    <?php if ($flashSuccess): ?>
                        <div role="status" style="margin-bottom:16px;padding:12px 18px;border:1px solid rgba(16,185,129,.35);border-radius:var(--radius-md);background:rgba(16,185,129,.1);color:#10b981;display:flex;align-items:center;gap:12px;font-size:0.9rem;">
                            <i class="fa-solid fa-circle-check" style="font-size:1.1rem;"></i>
                            <span><?= e($flashSuccess) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div role="alert" style="margin-bottom:16px;padding:12px 18px;border:1px solid rgba(239,68,68,.35);border-radius:var(--radius-md);background:rgba(239,68,68,.1);color:#ef4444;display:flex;align-items:center;gap:12px;font-size:0.9rem;">
                            <i class="fa-solid fa-circle-exclamation" style="font-size:1.1rem;"></i>
                            <span><?= e($flashError) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php require $viewFile; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        window.AppFlash = <?= json_encode(['success' => $flashSuccess, 'error' => $flashError], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="<?= url('assets/js/app.js?v=20261004-obe') ?>"></script>

    <!-- Theme Switcher & Accent Palette Handler -->
    <script>
        function applyTheme(theme, animate = false) {
            if (animate) {
                document.documentElement.classList.add('theme-transition');
            }
            
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.classList.remove('dark', 'light');
            document.documentElement.classList.add(theme);
            localStorage.setItem('theme', theme);

            // Update UI Topbar Button
            const icon = document.getElementById('themeToggleIcon');
            const text = document.getElementById('themeToggleText');
            const btn = document.getElementById('themeToggleBtn');

            if (icon && text && btn) {
                if (theme === 'light') {
                    icon.className = 'fa-solid fa-sun';
                    text.textContent = 'Light';
                    btn.setAttribute('title', 'Ganti ke Mode Gelap (Dark Mode)');
                    btn.setAttribute('aria-label', 'Switch to Dark Mode');
                } else {
                    icon.className = 'fa-solid fa-moon';
                    text.textContent = 'Dark';
                    btn.setAttribute('title', 'Ganti ke Mode Terang (Light Mode)');
                    btn.setAttribute('aria-label', 'Switch to Light Mode');
                }
            }

            // Dispatch global event untuk ApexCharts atau komponen lain
            window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));

            if (animate) {
                setTimeout(() => {
                    document.documentElement.classList.remove('theme-transition');
                }, 350);
            }
        }

        window.toggleTheme = function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme, true);
        };

        const paletteConfig = {
            slate: { name: 'Slate Blue', color: '#0284c7' },
            indigo: { name: 'Indigo', color: '#6366f1' },
            emerald: { name: 'Emerald', color: '#10b981' },
            ocean: { name: 'Ocean Blue', color: '#0ea5e9' },
            violet: { name: 'Violet', color: '#8b5cf6' },
            amber: { name: 'Amber', color: '#f59e0b' },
            rose: { name: 'Rose', color: '#f43f5e' }
        };

        window.applyPalette = function(palette, animate = true) {
            if (!paletteConfig[palette]) {
                palette = 'slate';
            }

            if (animate) {
                document.documentElement.classList.add('theme-transition');
            }

            document.documentElement.setAttribute('data-palette', palette);
            localStorage.setItem('accent_palette', palette);

            const nameEl = document.getElementById('paletteActiveName');
            const dotEl = document.getElementById('paletteActiveDot');
            if (nameEl) nameEl.textContent = paletteConfig[palette].name;
            if (dotEl) dotEl.style.backgroundColor = paletteConfig[palette].color;

            document.querySelectorAll('.palette-option').forEach(btn => {
                if (btn.getAttribute('data-pal') === palette) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            const menu = document.getElementById('paletteDropdownMenu');
            if (menu) menu.style.display = 'none';

            window.dispatchEvent(new CustomEvent('paletteChanged', { 
                detail: { palette, color: paletteConfig[palette].color } 
            }));

            if (animate) {
                setTimeout(() => {
                    document.documentElement.classList.remove('theme-transition');
                }, 350);
            }
        };

        window.togglePaletteDropdown = function(e) {
            if (e) e.stopPropagation();
            const userMenu = document.getElementById('userPillMenu');
            if (userMenu) userMenu.style.display = 'none';

            const menu = document.getElementById('paletteDropdownMenu');
            if (menu) {
                const isHidden = menu.style.display === 'none' || menu.style.display === '';
                menu.style.display = isHidden ? 'block' : 'none';
            }
        };

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('paletteDropdownWrapper');
            const menu = document.getElementById('paletteDropdownMenu');
            if (wrapper && menu && !wrapper.contains(e.target)) {
                menu.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'dark';
            applyTheme(currentTheme, false);

            const currentPalette = document.documentElement.getAttribute('data-palette') || localStorage.getItem('accent_palette') || 'slate';
            window.applyPalette(currentPalette, false);
        });
    </script>
</body>
</html>
