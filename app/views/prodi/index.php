<?php
$totalProdi = count($items);
$prodiAktif = count(array_filter($items, fn($p) => ($p['status'] ?? '') === 'aktif'));
$fakultasUnik = count(array_unique(array_filter(array_column($items, 'fakultas'))));
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">Data Program Studi</h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            Kelola program studi untuk pengelompokan kelas dan plotting jadwal per fakultas.
        </p>
    </div>
    <?php if (has_role(['admin'])): ?>
        <a href="<?= url('prodi/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Prodi
        </a>
    <?php endif; ?>
</div>

<!-- KPI Mini Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Program Studi</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= $totalProdi ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Prodi terdaftar</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Prodi Aktif</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin-top: 4px;"><?= $prodiAktif ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Menyelenggarakan kuliah</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Fakultas Terkelola</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-top: 4px;"><?= $fakultasUnik ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Unit fakultas penaung</div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 130px;">KODE PRODI</th>
                    <th>NAMA PROGRAM STUDI</th>
                    <th>FAKULTAS</th>
                    <th style="width: 130px; text-align: center;">STATUS</th>
                    <th style="width: 120px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <i class="fa-solid fa-graduation-cap" style="font-size: 2.2rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                            <span style="font-size: 0.95rem; font-weight: 500;">Belum ada data program studi.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <span class="badge-code"><?= e($item['kode_prodi']) ?></span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.92rem;"><?= e($item['nama_prodi']) ?></div>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.88rem;">
                                    <i class="fa-solid fa-building-columns mr-1" style="opacity: 0.6;"></i> <?= e($item['fakultas'] ?: '-') ?>
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
                                        <a href="<?= url('prodi/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-secondary" title="Edit Program Studi">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= url('prodi/delete?id=' . $item['id']) ?>" class="btn btn-sm btn-danger" data-confirm-delete title="Hapus Program Studi">
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
