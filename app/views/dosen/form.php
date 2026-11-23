<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Data Dosen Pengampu' : 'Tambah Dosen Pengampu Baru' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Perbarui informasi NIDN, identitas lengkap beserta gelar, atau email dinas dosen.' : 'Daftarkan dosen pengampu baru ke dalam sistem akademik untuk plotting mengajar.' ?>
        </p>
    </div>
    <a href="<?= url('dosen') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Master Dosen
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('dosen/update') : url('dosen/store') ?>" data-confirm-submit>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-chalkboard-user mr-1 text-primary"></i> Nama Lengkap Beserta Gelar <span class="required">*</span></span>
                    </label>
                    <input name="nama_dosen" class="form-control" value="<?= e($item['nama_dosen'] ?? '') ?>" placeholder="Misal: Dr. Ir. Budi Santoso, M.Kom." required>
                    <span class="form-hint">Tuliskan nama lengkap beserta seluruh gelar akademik.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-id-card mr-1 text-primary"></i> NIDN / NUPN</span>
                    </label>
                    <input name="nidn" class="form-control" value="<?= e($item['nidn'] ?? '') ?>" placeholder="Misal: 0712345678" style="font-family: monospace; font-weight: 600;">
                    <span class="form-hint">Nomor Induk Dosen Nasional (opsional).</span>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom: 0;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-regular fa-envelope mr-1 text-primary"></i> Alamat Email Kampus</span>
                    </label>
                    <input type="email" name="email" class="form-control" value="<?= e($item['email'] ?? '') ?>" placeholder="Misal: dosen@kampus.ac.id">
                    <span class="form-hint">Email aktif untuk korespondensi jadwal.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status Keaktifan Dosen</span>
                    </label>
                    <div class="radio-card-group">
                        <label class="radio-card-label">
                            <input type="radio" name="status" value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'checked' : '' ?>>
                            <span class="badge-status-active"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Aktif (Tersedia Mengajar)</span>
                        </label>
                        <label class="radio-card-label">
                            <input type="radio" name="status" value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'checked' : '' ?>>
                            <span class="badge-status-inactive"><span style="width:6px;height:6px;border-radius:50%;background:var(--text-muted);"></span> Nonaktif (Cuti / Belajar)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('dosen') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan' : 'Simpan Data Dosen' ?>
            </button>
        </div>
    </form>
</div>
