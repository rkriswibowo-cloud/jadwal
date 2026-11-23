<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah Baru' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Perbarui informasi kode, bobot kredit SKS, atau plotting semester kurikulum.' : 'Daftarkan kurikulum mata kuliah baru ke dalam sistem pemetaan perkuliahan kampus.' ?>
        </p>
    </div>
    <a href="<?= url('mata-kuliah') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Master Mata Kuliah
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('mata-kuliah/update') : url('mata-kuliah/store') ?>" data-confirm-submit>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-barcode mr-1 text-primary"></i> Kode Mata Kuliah <span class="required">*</span></span>
                    </label>
                    <input name="kode_mk" class="form-control" value="<?= e($item['kode_mk'] ?? '') ?>" placeholder="Misal: SI-201 / MK-001" required style="font-family: monospace; font-weight: 600; text-transform: uppercase;">
                    <span class="form-hint">Kode standar kurikulum.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-clock-rotate-left mr-1 text-primary"></i> Bobot SKS <span class="required">*</span></span>
                    </label>
                    <input type="number" min="0" max="10" name="sks" class="form-control" value="<?= e($item['sks'] ?? '3') ?>" placeholder="3" required>
                    <span class="form-hint">Satuan Kredit Semester (1-10).</span>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-book-open mr-1 text-primary"></i> Nama Mata Kuliah <span class="required">*</span></span>
                    </label>
                    <input name="nama_mk" class="form-control" value="<?= e($item['nama_mk'] ?? '') ?>" placeholder="Misal: Basis Data Terdistribusi" required>
                    <span class="form-hint">Nama lengkap mata kuliah sesuai kurikulum resmi.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-layer-group mr-1 text-primary"></i> Rekomendasi Semester</span>
                    </label>
                    <input type="number" min="1" max="14" name="semester" class="form-control" value="<?= e($item['semester'] ?? '') ?>" placeholder="Misal: 3">
                    <span class="form-hint">Semester plotting pada sebaran kurikulum prodi.</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">
                    <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status Mata Kuliah</span>
                </label>
                <div class="radio-card-group">
                    <label class="radio-card-label">
                        <input type="radio" name="status" value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'checked' : '' ?>>
                        <span class="badge-status-active"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Aktif (Dapat Dijadwalkan)</span>
                    </label>
                    <label class="radio-card-label">
                        <input type="radio" name="status" value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'checked' : '' ?>>
                        <span class="badge-status-inactive"><span style="width:6px;height:6px;border-radius:50%;background:var(--text-muted);"></span> Nonaktif (Kurikulum Lama / Arsip)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('mata-kuliah') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan' : 'Simpan Mata Kuliah' ?>
            </button>
        </div>
    </form>
</div>
