<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Data Program Studi' : 'Tambah Program Studi Baru' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Perbarui informasi kode, fakultas penaung, atau nama program studi.' : 'Daftarkan program studi baru ke dalam sistem akademik untuk pengelompokan kelas dan jadwal.' ?>
        </p>
    </div>
    <a href="<?= url('prodi') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('prodi/update') : url('prodi/store') ?>" data-confirm-submit>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-barcode mr-1 text-primary"></i> Kode Program Studi <span class="required">*</span></span>
                    </label>
                    <input name="kode_prodi" class="form-control" value="<?= e($item['kode_prodi'] ?? '') ?>" placeholder="Misal: SI / IF / TI" required style="font-family: monospace; font-weight: 600; text-transform: uppercase;">
                    <span class="form-hint">Kode singkatan prodi (maksimal 10 karakter).</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-building-columns mr-1 text-primary"></i> Fakultas Penaung</span>
                    </label>
                    <input name="fakultas" class="form-control" value="<?= e($item['fakultas'] ?? '') ?>" placeholder="Misal: Fakultas Ilmu Komputer">
                    <span class="form-hint">Unit pengelola program studi (UPPS).</span>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-graduation-cap mr-1 text-primary"></i> Nama Program Studi <span class="required">*</span></span>
                    </label>
                    <input name="nama_prodi" class="form-control" value="<?= e($item['nama_prodi'] ?? '') ?>" placeholder="Misal: Sistem Informasi" required>
                    <span class="form-hint">Nama lengkap program studi sesuai nomenklatur PDDIKTI.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status Penyelenggaraan Prodi</span>
                    </label>
                    <div class="radio-card-group" style="height: 42px;">
                        <label class="radio-card-label">
                            <input type="radio" name="status" value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'checked' : '' ?>>
                            <span class="badge-status-active"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Aktif</span>
                        </label>
                        <label class="radio-card-label">
                            <input type="radio" name="status" value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'checked' : '' ?>>
                            <span class="badge-status-inactive"><span style="width:6px;height:6px;border-radius:50%;background:var(--text-muted);"></span> Nonaktif</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('prodi') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan' : 'Simpan Program Studi' ?>
            </button>
        </div>
    </form>
</div>
