<?php
$totalKelas = count($items);
$totalMahasiswa = array_sum(array_column($items, 'jumlah_mahasiswa'));
$kelasAktif = count(array_filter($items, fn($k) => ($k['status'] ?? '') === 'aktif'));
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">Data Rombongan Belajar (Kelas)</h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            Kelola kelas perkuliahan, jenjang angkatan, semester, dan kapasitas mahasiswa.
        </p>
    </div>
    <?php if (has_role(['admin'])): ?>
        <a href="<?= url('kelas/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Kelas
        </a>
    <?php endif; ?>
</div>

<!-- KPI Mini Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Kelas</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= $totalKelas ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Rombongan belajar terdaftar</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Mahasiswa</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-top: 4px;"><?= number_format($totalMahasiswa) ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Mahasiswa aktif di kelas</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Kelas Aktif</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin-top: 4px;"><?= $kelasAktif ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Sedang berjalan semester ini</div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 120px;">KODE KELAS</th>
                    <th>NAMA KELAS</th>
                    <th>PROGRAM STUDI</th>
                    <th style="width: 110px; text-align: center;">ANGKATAN</th>
                    <th style="width: 100px; text-align: center;">SEMESTER</th>
                    <th style="width: 110px; text-align: center;">MAHASISWA</th>
                    <th style="width: 120px; text-align: center;">STATUS</th>
                    <th style="width: 120px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="8" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <i class="fa-solid fa-users-rectangle" style="font-size: 2.2rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                            <span style="font-size: 0.95rem; font-weight: 500;">Belum ada data rombongan belajar / kelas.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <span class="badge-code"><?= e($item['kode_kelas']) ?></span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.92rem;"><?= e($item['nama_kelas']) ?></div>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.88rem;">
                                    <?= e($item['nama_prodi']) ?>
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 600; color: var(--text-main); font-size: 0.88rem;">
                                <?= e($item['angkatan']) ?>
                            </td>
                            <td style="text-align: center;">
                                <span style="background: var(--surface); border: 1px solid var(--border); padding: 2px 8px; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 600; color: var(--text-main);">
                                    Smt <?= e($item['semester']) ?>
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 600; color: var(--text-main); font-size: 0.88rem;">
                                <?= e($item['jumlah_mahasiswa']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if (($item['status'] ?? '') === 'aktif'): ?>
                                    <span class="badge-status-active">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span> Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status-inactive">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--text-muted);"></span> Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <a href="<?= url('kelas/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-secondary" title="Edit Kelas">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if (has_role(['admin'])): ?>
                                        <a href="<?= url('kelas/delete?id=' . $item['id']) ?>" class="btn btn-sm btn-danger" data-confirm-delete title="Hapus Kelas">
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
