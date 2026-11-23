<?php
$totalJam = count($items);
$firstJam = !empty($items) ? time_short($items[0]['jam_mulai']) : '-';
$lastJam = !empty($items) ? time_short($items[count($items) - 1]['jam_selesai']) : '-';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">Data Slot Jam Kuliah</h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            Master interval jam perkuliahan standar untuk plotting sesi jadwal di tiap hari kerja.
        </p>
    </div>
    <?php if (has_role(['admin'])): ?>
        <a href="<?= url('jam/create') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Jam Kuliah
        </a>
    <?php endif; ?>
</div>

<!-- KPI Mini Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Slot Jam</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= $totalJam ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Sesi perkuliahan harian</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Sesi Terpagi</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin-top: 4px;"><?= $firstJam ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Waktu awal kuliah</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Sesi Terakhir</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-top: 4px;"><?= $lastJam ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Waktu akhir kuliah</div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 150px;">JAM KE</th>
                    <th style="width: 120px;">KODE</th>
                    <th style="width: 130px; text-align: center;">JAM MULAI</th>
                    <th style="width: 130px; text-align: center;">JAM SELESAI</th>
                    <th>KETERANGAN / INTERVAL</th>
                    <th style="width: 120px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <i class="fa-solid fa-clock" style="font-size: 2.2rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                            <span style="font-size: 0.95rem; font-weight: 500;">Belum ada slot jam kuliah.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $index => $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--text-main);">
                                    <span style="width: 26px; height: 26px; border-radius: 50%; background: rgba(var(--primary-rgb), 0.1); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; flex-shrink: 0;">
                                        <?= e(jam_number($item, $index + 1)) ?>
                                    </span>
                                    <span>Jam ke-<?= e(jam_number($item, $index + 1)) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-code">
                                    <?= e($item['kode_jam']) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-weight: 600; color: #10b981; font-family: monospace; font-size: 0.9rem;">
                                    <i class="fa-regular fa-clock mr-1" style="font-size: 0.75rem; opacity: 0.7;"></i>
                                    <?= e(time_short($item['jam_mulai'])) ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span style="font-weight: 600; color: var(--primary); font-family: monospace; font-size: 0.9rem;">
                                    <i class="fa-regular fa-clock mr-1" style="font-size: 0.75rem; opacity: 0.7;"></i>
                                    <?= e(time_short($item['jam_selesai'])) ?>
                                </span>
                            </td>
                            <td>
                                <span style="color: var(--text-muted); font-size: 0.88rem;">
                                    <?= e($item['keterangan'] ?: '-') ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <?php if (has_role(['admin'])): ?>
                                    <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                        <a href="<?= url('jam/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-secondary" title="Edit Jam">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= url('jam/delete?id=' . $item['id']) ?>" class="btn btn-sm btn-danger" data-confirm-delete title="Hapus Jam">
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
