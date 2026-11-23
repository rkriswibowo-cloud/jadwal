<?php
$totalDosen = count($items);
$dosenAktif = count(array_filter($items, fn($d) => ($d['status'] ?? '') === 'aktif'));
$dosenNidn = count(array_filter($items, fn($d) => !empty($d['nidn']) && $d['nidn'] !== '-'));
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">Data Dosen Pengampu</h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            Kelola data tenaga pendidik, NIDN, kontak email, dan cetak jadwal mengajar per dosen.
        </p>
    </div>
    <a href="<?= url('dosen/create') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Dosen
    </a>
</div>

<!-- KPI Mini Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Dosen</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--text-main); margin-top: 4px;"><?= $totalDosen ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Dosen terdaftar</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Dosen Aktif Mengajar</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin-top: 4px;"><?= $dosenAktif ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">Siap diplot perkuliahan</div>
    </div>
    <div class="card" style="padding: 18px 20px;">
        <div style="font-size: 0.76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Memiliki NIDN</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: var(--primary); margin-top: 4px;"><?= $dosenNidn ?></div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">NIDN terverifikasi</div>
    </div>
</div>

<div class="card" style="overflow: hidden;">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 140px;">NIDN</th>
                    <th>NAMA DOSEN</th>
                    <th>EMAIL KAMPUS</th>
                    <th style="width: 120px; text-align: center;">STATUS</th>
                    <th style="width: 180px; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" style="padding: 48px; text-align: center; color: var(--text-muted);">
                            <i class="fa-solid fa-chalkboard-user" style="font-size: 2.2rem; opacity: 0.35; margin-bottom: 12px; display: block;"></i>
                            <span style="font-size: 0.95rem; font-weight: 500;">Belum ada data dosen pengampu.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['nidn']) && $item['nidn'] !== '-'): ?>
                                    <span class="badge-code">
                                        <?= e($item['nidn']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">Tanpa NIDN</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main); font-size: 0.92rem; display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--surface); border: 1px solid var(--border); display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--primary); font-weight: 700; flex-shrink: 0;">
                                        <?= strtoupper(substr($item['nama_dosen'] ?? 'D', 0, 1)) ?>
                                    </div>
                                    <span><?= e($item['nama_dosen']) ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($item['email']) && $item['email'] !== '-'): ?>
                                    <span style="color: var(--text-muted); font-size: 0.88rem;">
                                        <i class="fa-regular fa-envelope mr-1" style="opacity: 0.6;"></i> <?= e($item['email']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.82rem; font-style: italic;">-</span>
                                <?php endif; ?>
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
                                    <a href="<?= url('jadwal/cetak-dosen?dosen_id=' . $item['id']) ?>" class="btn btn-sm btn-info" title="Cetak Jadwal Mengajar Dosen">
                                        <i class="fa-solid fa-print"></i> Jadwal
                                    </a>
                                    <a href="<?= url('dosen/edit?id=' . $item['id']) ?>" class="btn btn-sm btn-secondary" title="Edit Dosen">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if (has_role(['admin'])): ?>
                                        <a href="<?= url('dosen/delete?id=' . $item['id']) ?>" class="btn btn-sm btn-danger" data-confirm-delete title="Hapus Dosen">
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
