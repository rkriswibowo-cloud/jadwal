<?php
$totalRuang = count($items);
$ruangAktif = count(array_filter($items, fn($r) => ($r['status'] ?? '') === 'aktif'));
$totalKapasitas = array_sum(array_column($items, 'kapasitas'));
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">Data Ruang Kuliah</h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            Kelola data ruangan, kapasitas kursi, dan lokasi fisik untuk plotting perkuliahan.
        </p>
    </div>
    <?php if (has_role(['admin'])): ?>
        <a href="<?= url('ruang/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Ruang
        </a>
    <?php endif; ?>
</div>

<!-- KPI Mini Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Ruangan</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= $totalRuang ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Ruang kuliah terdata</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Ruang Aktif</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin-top: 4px;"><?= $ruangAktif ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Siap digunakan perkuliahan</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Daya Tampung Total</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-top: 4px;"><?= number_format($totalKapasitas) ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Total kapasitas kursi</div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 130px;">KODE RUANG</th>
                    <th>NAMA RUANG</th>
                    <th style="width: 140px; text-align: center;">KAPASITAS</th>
                    <th>LOKASI / GEDUNG</th>
                    <th style="width: 130px; text-align: center;">STATUS</th>
                    <th style="width: 120px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <i class="fa-solid fa-door-open" style="font-size: 2.2rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                            <span style="font-size: 0.95rem; font-weight: 500;">Belum ada data ruang kuliah.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <span class="badge-code"><?= e($item['kode_ruang']) ?></span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.92rem;"><?= e($item['nama_ruang']) ?></div>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-weight: 700; color: var(--text-main); font-size: 0.9rem;"><?= e($item['kapasitas']) ?></span>
                                <span style="color: var(--text-muted); font-size: 0.78rem;"> Kursi</span>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.88rem;">
                                    <i class="fa-solid fa-location-dot mr-1" style="opacity: 0.6;"></i> <?= e($item['lokasi'] ?: '-') ?>
                                </span>
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
                                <?php if (has_role(['admin'])): ?>
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <a href="<?= url('ruang/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-secondary" title="Edit Data Ruangan">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= url('ruang/delete?id=' . $item['id']) ?>" class="btn btn-sm btn-danger" data-confirm-delete title="Hapus Ruangan">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
