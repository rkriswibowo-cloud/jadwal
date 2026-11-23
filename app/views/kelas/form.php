<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Rombongan Belajar (Kelas)' : 'Tambah Kelas Baru' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Perbarui informasi kelas, semester aktif, dan kapasitas rombongan belajar.' : 'Daftarkan rombongan belajar baru untuk pemetaan plotting jadwal mata kuliah.' ?>
        </p>
    </div>
    <a href="<?= url('kelas') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Master Kelas
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('kelas/update') : url('kelas/store') ?>" data-confirm-submit>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>

            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-graduation-cap mr-1 text-primary"></i> Program Studi <span class="required">*</span></span>
                    </label>
                    <select name="prodi_id" class="form-control" required>
                        <option value="">-- Pilih Program Studi --</option>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= (($item['prodi_id'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                                <?= e($p['nama_prodi']) ?> (<?= e($p['kode_prodi']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint">Pilih prodi yang menaungi rombongan belajar ini.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-users-rectangle mr-1 text-primary"></i> Nama Kelas <span class="required">*</span></span>
                    </label>
                    <input name="nama_kelas" class="form-control" value="<?= e($item['nama_kelas'] ?? '') ?>" placeholder="Misal: Reguler Pagi A" required>
                    <span class="form-hint">Contoh: Reguler Pagi A, Malam B, Eksekutif.</span>
                </div>
            </div>

            <div class="form-grid-4" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-barcode mr-1 text-primary"></i> Kode Kelas <span class="required">*</span></span>
                    </label>
                    <input name="kode_kelas" class="form-control" value="<?= e($item['kode_kelas'] ?? '') ?>" placeholder="Misal: SI-3A / IF-1B" required style="font-family: monospace; font-weight: 600; text-transform: uppercase;">
                    <span class="form-hint">Kode pengenal unik rombel.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-regular fa-calendar mr-1 text-primary"></i> Tahun Angkatan</span>
                    </label>
                    <input type="number" name="angkatan" class="form-control" value="<?= e($item['angkatan'] ?? date('Y')) ?>" placeholder="2024">
                    <span class="form-hint">Tahun masuk mahasiswa.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-layer-group mr-1 text-primary"></i> Semester Aktif</span>
                    </label>
                    <input type="number" min="1" max="14" name="semester" class="form-control" value="<?= e($item['semester'] ?? '1') ?>" placeholder="1">
                    <span class="form-hint">Semester berjalan saat ini (1-14).</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-user-group mr-1 text-primary"></i> Jumlah Mahasiswa</span>
                    </label>
                    <input type="number" min="0" name="jumlah_mahasiswa" class="form-control" value="<?= e($item['jumlah_mahasiswa'] ?? '0') ?>" placeholder="35">
                    <span class="form-hint">Estimasi kuota peserta kelas.</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">
                    <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status Keaktifan Kelas</span>
                </label>
                <div class="radio-card-group">
                    <label class="radio-card-label">
                        <input type="radio" name="status" value="aktif" <?= (($item['status'] ?? 'aktif') === 'aktif') ? 'checked' : '' ?>>
                        <span class="badge-status-active"><span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Aktif (Berjalan Semester Ini)</span>
                    </label>
                    <label class="radio-card-label">
                        <input type="radio" name="status" value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'checked' : '' ?>>
                        <span class="badge-status-inactive"><span style="width:6px;height:6px;border-radius:50%;background:var(--text-muted);"></span> Nonaktif (Lulus / Diliburkan)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('kelas') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan' : 'Simpan Data Kelas' ?>
            </button>
        </div>
    </form>
</div>
