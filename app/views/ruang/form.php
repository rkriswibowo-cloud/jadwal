<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Data Ruang Kuliah' : 'Tambah Ruang Kuliah Baru' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Perbarui informasi kapasitas kursi, nama ruang, atau lokasi fisik ruang perkuliahan.' : 'Daftarkan ruangan perkuliahan baru ke dalam sistem pemetaan jadwal kampus.' ?>
        </p>
    </div>
    <a href="<?= url('ruang') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('ruang/update') : url('ruang/store') ?>" data-confirm-submit>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-barcode mr-1 text-primary"></i> Kode Ruang <span class="required">*</span></span>
                    </label>
                    <input name="kode_ruang" class="form-control" value="<?= e($item['kode_ruang'] ?? '') ?>" placeholder="Misal: R.301 / LAB-01" required style="font-family: monospace; font-weight: 600; text-transform: uppercase;">
                    <span class="form-hint">Kode unik identifikasi ruangan kelas.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-chair mr-1 text-primary"></i> Kapasitas Kursi <span class="required">*</span></span>
                    </label>
                    <input type="number" min="1" name="kapasitas" class="form-control" value="<?= e($item['kapasitas'] ?? '') ?>" placeholder="Misal: 40" required>
                    <span class="form-hint">Daya tampung maksimal mahasiswa.</span>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-door-open mr-1 text-primary"></i> Nama Ruang Kuliah <span class="required">*</span></span>
                    </label>
                    <input name="nama_ruang" class="form-control" value="<?= e($item['nama_ruang'] ?? '') ?>" placeholder="Misal: Ruang Teori 301 Gedung B" required>
                    <span class="form-hint">Nama deskriptif untuk kemudahan pengenalan jadwal.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-location-dot mr-1 text-primary"></i> Lokasi / Gedung / Lantai</span>
                    </label>
                    <input name="lokasi" class="form-control" value="<?= e($item['lokasi'] ?? '') ?>" placeholder="Misal: Gedung Terpadu Lt. 3">
                    <span class="form-hint">Informasi penempatan gedung atau lantai.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status Operasional Ruang</span>
                </label>
                <div class="radio-card-group">
                    <label class="radio-card-label">
                        <input type="radio" name="status" value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'checked' : '' ?>>
                        <span class="badge-status-active"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Aktif (Siap Digunakan)</span>
                    </label>
                    <label class="radio-card-label">
                        <input type="radio" name="status" value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'checked' : '' ?>>
                        <span class="badge-status-inactive"><span style="width:6px;height:6px;border-radius:50%;background:var(--text-muted);"></span> Nonaktif (Renovasi / Ditutup)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('ruang') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan' : 'Simpan Data Ruang' ?>
            </button>
        </div>
    </form>
</div>
