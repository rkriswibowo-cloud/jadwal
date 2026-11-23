<div class="login-toolbar">
    <a href="<?= url('') ?>" class="login-toolbar-button" title="Kembali ke portal jadwal">
        <i class="fa-solid fa-arrow-left"></i><span>Portal</span>
    </a>
    <button type="button" class="login-toolbar-button" onclick="toggleLoginTheme()" title="Ubah tema">
        <i id="loginThemeIcon" class="fa-solid fa-moon"></i><span id="loginThemeText">Gelap</span>
    </button>
</div>

<div class="login-split">
    <section class="login-form-panel">
        <div class="login-form-wrap">
            <div class="login-brand login-reveal delay-1">
                <span class="login-brand-icon"><i class="fa-solid fa-calendar-days"></i></span>
                <span>
                    <strong>JADWAL KULIAH</strong>
                    <small>Sistem Informasi Akademik</small>
                </span>
            </div>

            <header class="login-heading">
                <h1 class="login-reveal delay-2"><span>Selamat datang</span> kembali</h1>
                <p class="login-reveal delay-3">Kelola jadwal perkuliahan dan penggunaan ruang dalam satu sistem terpadu.</p>
            </header>

            <form method="post" action="<?= url('login') ?>" class="login-form">
                <input type="hidden" name="redirect_to" value="login">

                <div class="login-field login-reveal delay-4">
                    <label for="usernameInput">Username / Email / NIDN</label>
                    <div class="login-glass-input">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" id="usernameInput" name="username" placeholder="Masukkan identitas akun" autocomplete="username" required autofocus>
                    </div>
                </div>

                <div class="login-field login-reveal delay-5">
                    <label for="loginPassword">Password</label>
                    <div class="login-glass-input">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="loginPassword" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button type="button" class="login-password-toggle" onclick="toggleLoginPassword()" aria-label="Tampilkan password" title="Tampilkan password">
                            <i id="loginPasswordEye" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="login-form-meta login-reveal delay-6">
                    <span><i class="fa-solid fa-shield-halved"></i> Akses aman dan terproteksi</span>
                    <button type="button" onclick="showLoginHelp()">Bantuan login</button>
                </div>

                <button type="submit" class="login-submit login-reveal delay-7">
                    <span>Masuk ke Sistem</span><i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <p class="login-contact login-reveal delay-8">Belum memiliki akun? <button type="button" onclick="showLoginHelp()">Hubungi administrator akademik</button></p>
        </div>
    </section>

    <section class="login-visual-panel" aria-label="Informasi sistem penjadwalan">
        <div class="login-visual-card">
            <div class="login-orb login-orb-one"></div>
            <div class="login-orb login-orb-two"></div>

            <div class="login-live-badge login-slide-in">
                <span></span> Sistem Penjadwalan Aktif
            </div>

            <div class="login-visual-copy login-slide-in delay-3">
                <span class="login-visual-kicker">PENJADWALAN TERPADU</span>
                <h2>Ruang tepat.<br>Waktu tertata.</h2>
                <p>Pantau agenda kuliah, alokasi ruang, dan dosen pengampu secara cepat dan akurat.</p>
            </div>

            <div class="login-schedule-board login-board-in delay-5">
                <div class="login-board-top">
                    <div>
                        <small>Agenda Perkuliahan</small>
                        <strong>Jadwal Hari Ini</strong>
                    </div>
                    <span><i class="fa-regular fa-calendar"></i> Terbarui</span>
                </div>
                <div class="login-board-days"><span>Sen</span><span class="active">Sel</span><span>Rab</span><span>Kam</span><span>Jum</span></div>
                <div class="login-board-row">
                    <time>08:00</time><i class="blue"></i>
                    <div><strong>Basis Data</strong><small>Ruang Lab 02 · Kelas SI-4A</small></div>
                    <span>Aktif</span>
                </div>
                <div class="login-board-row">
                    <time>10:30</time><i class="violet"></i>
                    <div><strong>Rekayasa Perangkat Lunak</strong><small>Ruang 304 · Kelas SI-6B</small></div>
                    <span>Berikutnya</span>
                </div>
                <div class="login-board-row">
                    <time>13:00</time><i class="emerald"></i>
                    <div><strong>Interaksi Manusia Komputer</strong><small>Ruang 201 · Kelas SI-4B</small></div>
                    <span>Terjadwal</span>
                </div>
            </div>

            <div class="login-feature-chips login-board-in delay-7">
                <span><i class="fa-solid fa-door-open"></i> Kelola Ruang</span>
                <span><i class="fa-solid fa-users"></i> Plotting Kelas</span>
                <span><i class="fa-solid fa-print"></i> Cetak Jadwal</span>
            </div>
        </div>
    </section>
</div>

<script>
function toggleLoginPassword() {
    const input = document.getElementById('loginPassword');
    const eye = document.getElementById('loginPasswordEye');
    const button = eye ? eye.closest('button') : null;
    if (!input || !eye || !button) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    eye.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
}

function applyLoginTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.classList.remove('dark', 'light');
    document.documentElement.classList.add(theme);
    localStorage.setItem('theme', theme);
    const icon = document.getElementById('loginThemeIcon');
    const text = document.getElementById('loginThemeText');
    if (icon && text) {
        icon.className = theme === 'dark' ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
        text.textContent = theme === 'dark' ? 'Gelap' : 'Terang';
    }
}

function toggleLoginTheme() {
    applyLoginTheme((document.documentElement.getAttribute('data-theme') || 'dark') === 'dark' ? 'light' : 'dark');
}

function showLoginHelp() {
    const message = '<div class="login-help-copy"><p><strong>Admin & Operator</strong><br>Gunakan email atau nama akun yang diberikan administrator.</p><p><strong>Dosen</strong><br>Gunakan NIDN atau email yang telah terhubung dengan sistem.</p><p>Jika kredensial bermasalah, hubungi administrator akademik kampus.</p></div>';
    if (typeof Swal !== 'undefined') {
        Swal.fire({ title: 'Bantuan Login', html: message, icon: 'info', confirmButtonText: 'Mengerti', confirmButtonColor: '#4f46e5' });
    } else {
        alert('Gunakan email, nama akun, atau NIDN yang terdaftar. Hubungi administrator akademik jika akun bermasalah.');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    applyLoginTheme(localStorage.getItem('theme') || 'dark');
});
</script>
