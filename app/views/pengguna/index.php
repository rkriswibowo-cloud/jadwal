<header style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px">
    <div>
        <h1 style="margin:0;font-size:1.5rem">Manajemen Pengguna</h1>
        <p style="margin:4px 0 0;color:var(--text-muted)">Kelola peran, program studi, dan status akses pengguna.</p>
    </div>
    <a href="<?= e(url('pengguna/create')) ?>" class="btn btn-primary">
        <i class="fa-solid fa-user-plus" aria-hidden="true"></i> Tambah Pengguna
    </a>
</header>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Email</th><th>Peran</th><th>Program Studi</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $own = (int)$item['id'] === (int)user()['id']; ?>
                    <tr>
                        <td><strong><?= e($item['name']) ?></strong><?= $own ? ' (Anda)' : '' ?></td>
                        <td><?= e($item['email']) ?></td>
                        <td><?= e(account_role_label($item['role'])) ?></td>
                        <td><?= e($item['nama_prodi'] ?? '—') ?></td>
                        <td>
                            <span class="<?= $item['status'] === 'aktif' ? 'badge-status-active' : 'badge-status-inactive' ?>">
                                <?= $item['status'] === 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                <a class="btn btn-sm btn-secondary"
                                   href="<?= e(url($own ? 'profil' : 'pengguna/edit?id=' . $item['id'])) ?>">
                                    <?= $own ? 'Profil Saya' : 'Edit' ?>
                                </a>
                                <?php if (!$own): ?>
                                    <form method="post" action="<?= e(url('pengguna/delete')) ?>" data-confirm-submit
                                          data-confirm-title="Hapus pengguna?" data-confirm-text="Akun akan dihapus dan aksesnya dicabut.">
                                        <input type="hidden" name="csrf_token" value="<?= e(account_csrf_token()) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                aria-label="Hapus <?= e($item['name']) ?>">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$items): ?><tr><td colspan="6">Belum ada pengguna.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
