<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">Daftar Jadwal Kuliah</h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            Pemetaan ruang, hari, jam, kelas rombel, dan dosen pengampu perkuliahan kampus.
        </p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="<?= url('jadwal/cetak') ?>" class="btn btn-secondary" title="Cetak Matriks Jadwal Per Kelas">
            <i class="fa-solid fa-users"></i> Cetak Per Kelas
        </a>
        <a href="<?= url('jadwal/cetak-dosen') ?>" class="btn btn-secondary" title="Cetak Matriks Jadwal Per Dosen">
            <i class="fa-solid fa-chalkboard-user"></i> Cetak Per Dosen
        </a>
        <?php if (has_role(['admin'])): ?>
            <a href="<?= url('jadwal/create') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Jadwal
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filter Card -->
<div class="card" style="margin-bottom: 20px;">
    <div style="padding: 18px 20px;">
        <form method="get" action="<?= url('jadwal') ?>" class="jadwal-filter-form">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; align-items: flex-end;">
                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-regular fa-calendar-days mr-1 text-primary"></i> Hari</span>
                    </label>
                    <select name="hari" class="form-control" style="height: 38px;">
                        <option value="">Semua Hari</option>
                        <?php foreach (($days ?? academic_days()) as $day): ?>
                            <option value="<?= e($day) ?>" <?= (($filters['hari'] ?? '') === $day) ? 'selected' : '' ?>><?= e($day) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-solid fa-door-open mr-1 text-primary"></i> Ruang Kuliah</span>
                    </label>
                    <select name="ruang_id" class="form-control" style="height: 38px;">
                        <option value="0">Semua Ruang</option>
                        <?php foreach (($ruang ?? []) as $r): ?>
                            <option value="<?= e($r['id']) ?>" <?= ((int) ($filters['ruang_id'] ?? 0) === (int) $r['id']) ? 'selected' : '' ?>><?= e($r['nama_ruang']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-solid fa-graduation-cap mr-1 text-primary"></i> Program Studi</span>
                    </label>
                    <select name="prodi_id" class="form-control" style="height: 38px;">
                        <option value="0">Semua Prodi</option>
                        <?php foreach (($prodi ?? []) as $p): ?>
                            <option value="<?= e($p['id']) ?>" <?= ((int) ($filters['prodi_id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>><?= e($p['nama_prodi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-regular fa-calendar mr-1 text-primary"></i> Tahun Akademik</span>
                    </label>
                    <select name="tahun_akademik" class="form-control" style="height: 38px;">
                        <option value="">Semua Tahun</option>
                        <?php foreach (($years ?? []) as $year): ?>
                            <option value="<?= e($year) ?>" <?= (($filters['tahun_akademik'] ?? '') === $year) ? 'selected' : '' ?>><?= e($year) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status</span>
                    </label>
                    <select name="status" class="form-control" style="height: 38px;">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?= (($filters['status'] ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= (($filters['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; height: 38px;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="<?= url('jadwal') ?>" class="btn btn-secondary" style="height: 38px; padding: 0 14px;" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Schedule Table Card -->
<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 100px;">HARI</th>
                    <th style="width: 140px;">WAKTU</th>
                    <th>RUANG</th>
                    <th>KELAS</th>
                    <th>PROGRAM STUDI</th>
                    <th>MATA KULIAH</th>
                    <th>DOSEN</th>
                    <th style="width: 110px; text-align: center;">TAHUN</th>
                    <th style="width: 110px; text-align: center;">STATUS</th>
                    <th style="width: 110px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="10" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <i class="fa-solid fa-calendar-xmark" style="font-size: 2.2rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                            <span style="font-size: 0.95rem; font-weight: 500;">Tidak ada jadwal kuliah yang cocok dengan kriteria filter.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <span style="background: rgba(var(--primary-rgb), 0.1); border: 1px solid rgba(var(--primary-rgb), 0.25); color: var(--primary); padding: 3px 8px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-regular fa-calendar" style="font-size: 0.72rem;"></i> <?= e($item['hari']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.85rem; font-weight: 600; color: var(--text-main);">
                                    <?= e(time_short($item['jam_mulai'])) ?> - <?= e(time_short($item['jam_selesai'])) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.88rem;">
                                    <?= e($item['nama_ruang']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge-code">
                                    <?= e($item['kode_kelas']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.85rem;">
                                    <?= e($item['nama_prodi']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.9rem;">
                                    <?= e($item['mata_kuliah']) ?>
                                </div>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.86rem;">
                                    <?= e($item['dosen'] ?: '-') ?>
                                </span>
                            </td>
                            <td style="text-align: center; font-size: 0.82rem; color: var(--text-muted);">
                                <?= e($item['tahun_akademik']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if (($item['status'] ?? '') === 'aktif'): ?>
                                    <span class="badge-status-active">
                                        <span style="width: 5px; height: 5px; border-radius: 50%; background: #10b981;"></span> Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status-inactive">
                                        <span style="width: 5px; height: 5px; border-radius: 50%; background: var(--text-muted);"></span> Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="<?= url('jadwal/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-secondary" title="Edit Jadwal">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if (has_role(['admin'])): ?>
                                        <a href="<?= url('jadwal/delete?id=' . $item['id']) ?>" class="btn btn-sm btn-danger" data-confirm-delete title="Hapus Jadwal">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
