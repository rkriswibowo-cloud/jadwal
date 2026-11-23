<?php
$value = static function (string $key) use ($account): string {
    $submitted = $_POST[$key] ?? null;
    return is_string($submitted) ? $submitted : (string)($account[$key] ?? '');
};
?>
<header style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px">
    <h1 style="margin:0;font-size:1.5rem"><?= e($title) ?></h1>
    <a class="btn btn-secondary" href="<?= e(url('pengguna')) ?>">Kembali ke Manajemen Pengguna</a>
</header>

<div class="card">
    <form method="post" action="<?= e(url($account ? 'pengguna/update' : 'pengguna/store')) ?>" data-confirm-submit>
        <input type="hidden" name="csrf_token" value="<?= e(account_csrf_token()) ?>">
        <?php if ($account): ?><input type="hidden" name="id" value="<?= (int)$account['id'] ?>"><?php endif; ?>

        <div class="form-card-body">
            <div class="form-grid-2">
                <div>
                    <label for="account-name" class="form-label">Nama lengkap</label>
                    <input id="account-name" name="name" class="form-control" maxlength="120" required value="<?= e($value('name')) ?>">
                </div>
                <div>
                    <label for="account-email" class="form-label">Email</label>
                    <input type="email" id="account-email" name="email" class="form-control" maxlength="120" required value="<?= e($value('email')) ?>">
                </div>
                <div>
                    <label for="account-role" class="form-label">Peran</label>
                    <select id="account-role" name="role_id" class="form-control" required>
                        <option value="">Pilih peran</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= (int)$role['id'] ?>" data-role="<?= e($role['name']) ?>"
                                <?= (string)$role['id'] === $value('role_id') ? 'selected' : '' ?>>
                                <?= e(account_role_label($role['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="account-status" class="form-label">Status</label>
                    <select id="account-status" name="status" class="form-control">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif" <?= $value('status') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
                <div id="account-program-row">
                    <label for="account-program" class="form-label">Program studi</label>
                    <select id="account-program" name="prodi_id" class="form-control" aria-describedby="account-program-hint">
                        <option value="">Pilih program studi</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= (int)$program['id'] ?>" <?= (string)$program['id'] === $value('prodi_id') ? 'selected' : '' ?>>
                                <?= e($program['nama_prodi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span id="account-program-hint" class="form-hint">Wajib untuk Operator Prodi.</span>
                </div>
            </div>
            <hr>
            <div class="form-grid-2">
                <div>
                    <label for="account-password" class="form-label"><?= $account ? 'Kata sandi baru' : 'Kata sandi' ?></label>
                    <input type="password" id="account-password" name="password" autocomplete="new-password" class="form-control"
                           minlength="12" maxlength="72" <?= $account ? '' : 'required' ?> aria-describedby="account-password-hint">
                    <span id="account-password-hint" class="form-hint">
                        Minimal 12 karakter.<?= $account ? ' Kosongkan jika tidak mengganti kata sandi.' : '' ?>
                    </span>
                </div>
                <div>
                    <label for="account-confirm" class="form-label">Konfirmasi kata sandi</label>
                    <input type="password" id="account-confirm" name="confirm_password" autocomplete="new-password"
                           class="form-control" minlength="12" maxlength="72" <?= $account ? '' : 'required' ?>>
                </div>
            </div>
        </div>
        <div class="form-card-footer">
            <a class="btn btn-secondary" href="<?= e(url('pengguna')) ?>">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
        </div>
    </form>
</div>
<script>
(() => {
    const role = document.getElementById('account-role');
    const row = document.getElementById('account-program-row');
    const program = document.getElementById('account-program');
    function updateProgram() {
        const operator = role.selectedOptions[0]?.dataset.role === 'operator_prodi';
        row.hidden = !operator;
        program.disabled = !operator;
        program.required = operator;
    }
    role.addEventListener('change', updateProgram);
    updateProgram();
})();
</script>
