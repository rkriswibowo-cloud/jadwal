<header style="margin-bottom:24px">
    <h1 style="margin:0;font-size:1.5rem">Profil Saya</h1>
    <p style="margin:4px 0 0;color:var(--text-muted)">
        <?= e(account_role_label($account['role'])) ?><?= $account['nama_prodi'] ? ' · ' . e($account['nama_prodi']) : '' ?>
    </p>
</header>

<div class="card">
    <form method="post" action="<?= e(url('profil/update')) ?>" data-confirm-submit>
        <input type="hidden" name="csrf_token" value="<?= e(account_csrf_token()) ?>">
        <div class="form-card-body">
            <div class="form-grid-2">
                <div>
                    <label for="profile-name" class="form-label">Nama lengkap</label>
                    <input id="profile-name" name="name" class="form-control" autocomplete="name"
                           maxlength="120" required value="<?= e($account['name']) ?>">
                </div>
                <div>
                    <label for="profile-email" class="form-label">Email</label>
                    <input type="email" id="profile-email" name="email" class="form-control" autocomplete="email"
                           maxlength="120" required value="<?= e($account['email']) ?>">
                </div>
            </div>
            <hr>
            <div class="form-grid-2">
                <div>
                    <label for="profile-current-password" class="form-label">Kata sandi saat ini</label>
                    <input type="password" id="profile-current-password" name="current_password" class="form-control"
                           autocomplete="current-password" required>
                    <span class="form-hint">Diperlukan untuk menyimpan perubahan profil.</span>
                </div>
            </div>
            <div class="form-grid-2" style="margin-top:16px">
                <div>
                    <label for="profile-password" class="form-label">Kata sandi baru</label>
                    <input type="password" id="profile-password" name="password" class="form-control"
                           autocomplete="new-password" minlength="12" maxlength="72">
                    <span class="form-hint">Minimal 12 karakter. Kosongkan untuk memakai kata sandi saat ini.</span>
                </div>
                <div>
                    <label for="profile-confirm" class="form-label">Konfirmasi kata sandi baru</label>
                    <input type="password" id="profile-confirm" name="confirm_password" class="form-control"
                           autocomplete="new-password" minlength="12" maxlength="72">
                </div>
            </div>
        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary">Simpan Profil</button>
        </div>
    </form>
</div>
