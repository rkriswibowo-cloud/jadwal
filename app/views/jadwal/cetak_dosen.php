<?php
$matrix = [];
$prodiList = [];
foreach ($items as $row) {
    $matrix[(int) $row['jam_id']][$row['hari']][] = $row;
    if (!empty($row['nama_prodi'])) {
        foreach (explode(',', (string) $row['nama_prodi']) as $pName) {
            $pName = trim($pName);
            if ($pName !== '' && !in_array($pName, $prodiList, true)) {
                $prodiList[] = $pName;
            }
        }
    }
}
$prodiSummary = $prodiList ? implode(', ', $prodiList) : '-';
$hasSelectedReport = $selectedDosen !== null;

// Hilangkan hari Sabtu dan Minggu sesuai permintaan (Hanya Senin s/d Jumat)
$printDays = array_values(array_filter($days ?? academic_days(), function ($d) {
    return !in_array($d, ['Sabtu', 'Minggu'], true);
}));
?>

<style>
/* CSS Khusus Lembar Cetak Jadwal Dosen (Screen & Print Ultra-Optimized) */
.schedule-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: var(--radius-md);
}
.print-schedule-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    font-size: 0.82rem;
}
.print-schedule-table th,
.print-schedule-table td {
    border: 1px solid var(--border);
    vertical-align: top;
    padding: 6px 8px;
}
.print-schedule-table th {
    background: var(--surface-hover);
    color: var(--text-main) !important;
    font-weight: 700;
    text-align: center;
    vertical-align: middle;
}
.print-schedule-table th.print-col-hari-title,
[data-theme="dark"] .print-schedule-table th.print-col-hari-title,
[data-theme="light"] .print-schedule-table th.print-col-hari-title {
    background: var(--surface) !important;
    color: var(--primary) !important;
    font-weight: 800 !important;
    text-align: center;
    font-size: 0.8rem;
    padding: 6px;
}
.print-schedule-table th.print-col-day,
[data-theme="dark"] .print-schedule-table th.print-col-day,
[data-theme="light"] .print-schedule-table th.print-col-day {
    background: var(--surface-hover) !important;
    color: var(--text-main) !important;
    font-weight: 700 !important;
    text-align: center;
    font-size: 0.78rem;
    padding: 6px;
}
.print-col-jam, .print-time {
    width: 100px;
    max-width: 100px;
    text-align: center;
    background: var(--surface);
}
.print-time div {
    font-weight: 700;
    color: var(--text-main);
    font-size: 0.8rem;
}
.print-time small {
    color: var(--text-muted);
    font-size: 0.72rem;
}
.print-empty {
    color: var(--text-muted);
    opacity: 0.35;
    text-align: center;
    display: block;
}
.print-schedule-entry {
    display: block;
    padding: 5px 7px;
    border: 1px solid var(--border);
    border-left: 3px solid var(--primary);
    border-radius: var(--radius-sm);
    background: var(--surface-hover);
    margin-bottom: 4px;
}
.print-schedule-entry:last-child {
    margin-bottom: 0;
}
.entry-mk {
    color: var(--text-main);
    font-size: 0.8rem;
    font-weight: 700;
    line-height: 1.25;
}
.entry-sub {
    color: var(--text-muted);
    font-size: 0.72rem;
    line-height: 1.2;
    margin-top: 2px;
}
.entry-dot {
    margin: 0 3px;
    color: var(--text-muted);
}
.entry-prodi {
    color: var(--primary);
    font-size: 0.7rem;
    font-weight: 600;
    line-height: 1.2;
    margin-top: 2px;
}
.entry-badge {
    color: var(--warning);
    font-size: 0.68rem;
    font-weight: 600;
    background: rgba(245, 158, 11, 0.12);
    padding: 1px 4px;
    border-radius: 2px;
    display: inline-block;
    margin-top: 2px;
}
.print-doc-header {
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1.5px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 8px;
}
.print-doc-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--text-main);
    letter-spacing: -0.01em;
}
.print-doc-sub {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    display: block;
}
.print-doc-meta {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 500;
    text-align: right;
}
.print-signature-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 18px;
    padding-top: 12px;
    border-top: 1px dashed var(--border);
}
.print-date-info {
    font-size: 0.78rem;
    color: var(--text-muted);
}
.print-signature-box {
    text-align: center;
    min-width: 190px;
    font-size: 0.82rem;
}
.print-signature-space {
    height: 40px;
}
.print-signature-name {
    font-weight: 700;
    text-decoration: underline;
    color: var(--text-main);
}
.print-signature-nidn {
    font-size: 0.76rem;
    color: var(--text-muted);
}

/* ATURAN MEDIA PRINT KHUSUS A4 LANDSCAPE (1 HALAMAN PENUH, TANPA SCROLLBAR, TANPA GAP) */
@media print {
    @page {
        size: A4 landscape;
        margin: 4mm 6mm 4mm 6mm !important;
    }
    
    html, body {
        background: #ffffff !important;
        background-image: none !important;
        color: #000000 !important;
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .app-layout,
    .app-container,
    .main-content,
    .content-area {
        display: block !important;
        position: static !important;
        background: transparent !important;
        color: #000000 !important;
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Sembunyikan semua UI non-cetak */
    .topbar,
    .floating-sidebar,
    .sidebar,
    .jadwal-print-filter,
    .no-print,
    .alert,
    button,
    .btn,
    nav,
    header,
    footer {
        display: none !important;
    }

    .jadwal-print-sheet {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: #ffffff !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
    }

    .print-doc-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-end !important;
        border-bottom: 1.5px solid #000000 !important;
        padding-bottom: 3px !important;
        margin-bottom: 4px !important;
        page-break-after: avoid !important;
        break-after: avoid !important;
    }

    .print-doc-sub {
        font-size: 6.5pt !important;
        font-weight: 700 !important;
        color: #334155 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        display: block !important;
        line-height: 1.1 !important;
    }

    .print-doc-title {
        font-size: 10pt !important;
        font-weight: 800 !important;
        color: #000000 !important;
        margin: 1px 0 0 !important;
        line-height: 1.15 !important;
    }

    .print-doc-meta {
        font-size: 6.8pt !important;
        color: #000000 !important;
        font-weight: 600 !important;
        text-align: right !important;
        line-height: 1.15 !important;
        white-space: nowrap !important;
    }

    .schedule-table-wrap,
    .table-responsive {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
        border: none !important;
    }

    .print-schedule-table {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        margin: 0 !important;
        border: 1.5px solid #000000 !important;
        page-break-inside: auto !important;
        break-inside: auto !important;
        font-size: 6.8pt !important;
    }

    .print-schedule-table th,
    .print-schedule-table td {
        border: 1px solid #475569 !important;
        padding: 1.5px 2px !important;
        color: #000000 !important;
        background: transparent !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
    }

    .print-schedule-table th,
    [data-theme="dark"] .print-schedule-table th,
    [data-theme="light"] .print-schedule-table th {
        background: #f1f5f9 !important;
        font-weight: 700 !important;
        font-size: 7.2pt !important;
        color: #000000 !important;
        text-align: center !important;
        padding: 2.5px 1.5px !important;
        vertical-align: middle !important;
    }

    .print-schedule-table th.print-col-hari-title,
    [data-theme="dark"] .print-schedule-table th.print-col-hari-title,
    [data-theme="light"] .print-schedule-table th.print-col-hari-title {
        background: #e2e8f0 !important;
        color: #000000 !important;
        font-weight: 800 !important;
        font-size: 7.2pt !important;
        text-align: center !important;
        padding: 2px 2px !important;
        letter-spacing: 0.05em !important;
    }

    .print-schedule-table th.print-col-day,
    [data-theme="dark"] .print-schedule-table th.print-col-day,
    [data-theme="light"] .print-schedule-table th.print-col-day {
        background: #f1f5f9 !important;
        color: #000000 !important;
        font-weight: 700 !important;
        font-size: 7pt !important;
        text-align: center !important;
        padding: 1.5px 1.5px !important;
    }

    .print-col-jam,
    .print-time,
    [data-theme="dark"] .print-col-jam,
    [data-theme="light"] .print-col-jam {
        width: 72px !important;
        max-width: 72px !important;
        text-align: center !important;
        background: #f8fafc !important;
        color: #000000 !important;
    }

    .print-time div {
        font-size: 7pt !important;
        font-weight: 700 !important;
        color: #000000 !important;
        line-height: 1.05 !important;
    }

    .print-time small {
        font-size: 5.8pt !important;
        color: #334155 !important;
        line-height: 1 !important;
        display: block !important;
    }

    .print-empty {
        color: #cbd5e1 !important;
        font-size: 6.5pt !important;
        display: block !important;
        text-align: center !important;
    }

    .print-schedule-entry {
        display: block !important;
        text-align: left !important;
        padding: 1.5px 2.5px !important;
        margin: 1px 0 !important;
        border: 1px solid #94a3b8 !important;
        border-left: 2.5px solid #0284c7 !important;
        background: #f0f9ff !important;
        border-radius: 2px !important;
        line-height: 1.1 !important;
    }

    .entry-mk {
        font-size: 6.8pt !important;
        font-weight: 700 !important;
        color: #000000 !important;
        line-height: 1.1 !important;
    }

    .entry-sub {
        font-size: 5.8pt !important;
        color: #334155 !important;
        line-height: 1.05 !important;
        margin-top: 1px !important;
    }

    .entry-dot {
        margin: 0 1px !important;
    }

    .entry-prodi {
        font-size: 5.8pt !important;
        color: #0369a1 !important;
        font-weight: 600 !important;
        line-height: 1.05 !important;
        margin-top: 1px !important;
    }

    .entry-badge {
        font-size: 5.5pt !important;
        color: #92400e !important;
        background: #fef3c7 !important;
        border: 1px solid #fde68a !important;
        font-weight: 600 !important;
        padding: 0 2px !important;
        display: inline-block !important;
        margin-top: 1px !important;
    }

    .print-signature-section {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-end !important;
        margin-top: 6px !important;
        padding-top: 3px !important;
        border-top: 1px dashed #94a3b8 !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    .print-date-info {
        font-size: 6.2pt !important;
        color: #64748b !important;
    }

    .print-signature-box {
        text-align: center !important;
        font-size: 6.8pt !important;
        min-width: 160px !important;
        line-height: 1.15 !important;
    }

    .print-signature-space {
        height: 26px !important;
    }

    .print-signature-name {
        font-size: 7.2pt !important;
        font-weight: 700 !important;
        text-decoration: underline !important;
        color: #000000 !important;
    }

    .print-signature-nidn {
        font-size: 6.2pt !important;
        color: #334155 !important;
    }
}
</style>

<!-- Action Bar & Mode Switcher (Hidden in Print) -->
<div class="jadwal-print-filter" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h2 style="margin: 0; font-size: 1.4rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            Cetak Jadwal Mengajar Dosen
        </h2>
        <p style="color: var(--text-muted); margin: 3px 0 0; font-size: 0.86rem;">
            <?= $selectedDosen ? 'Jadwal mengajar resmi untuk dosen: <strong style="color: var(--text-main);">' . e($selectedDosen['nama_dosen']) . '</strong>' : 'Pilih dosen pengampu untuk menampilkan tabel jadwal mengajar.' ?>
        </p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <a href="<?= url('jadwal/cetak') ?>" class="btn btn-secondary" style="font-size: 0.85rem;">
            <i class="fa-solid fa-users"></i> Cetak Per Kelas
        </a>
        <a href="<?= url('jadwal') ?>" class="btn btn-secondary" style="font-size: 0.85rem;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Jadwal
        </a>
        <button type="button" class="btn btn-primary" data-print-page <?= $hasSelectedReport ? '' : 'disabled' ?> style="font-size: 0.85rem; box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.25);">
            <i class="fa-solid fa-print"></i> Cetak A4 / PDF
        </button>
    </div>
</div>

<!-- Filter Selection Form (Hidden in Print) -->
<div class="card jadwal-print-filter" style="margin-bottom: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); width: 100%;">
    <div style="padding: 16px 20px;">
        <form method="get" action="<?= url('jadwal/cetak-dosen') ?>">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; align-items: flex-end;">
                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-solid fa-chalkboard-user mr-1 text-primary"></i> Dosen Pengampu <span class="required">*</span></span>
                    </label>
                    <select name="dosen_id" class="form-control" required style="height: 40px;">
                        <option value="">-- Pilih Dosen Pengampu --</option>
                        <?php foreach ($dosenList as $d): ?>
                            <option value="<?= e($d['id']) ?>" <?= (int) $selectedDosenId === (int) $d['id'] ? 'selected' : '' ?>>
                                <?= e(trim(($d['nidn'] ? $d['nidn'] . ' - ' : '') . $d['nama_dosen'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-solid fa-layer-group mr-1 text-primary"></i> Semester</span>
                    </label>
                    <select name="semester_akademik" class="form-control" style="height: 40px;">
                        <option value="" <?= $selectedSemester === '' ? 'selected' : '' ?>>Semua Semester</option>
                        <option value="Ganjil" <?= $selectedSemester === 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                        <option value="Genap" <?= $selectedSemester === 'Genap' ? 'selected' : '' ?>>Genap</option>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="font-size: 0.78rem; text-transform: uppercase;">
                        <span><i class="fa-regular fa-calendar mr-1 text-primary"></i> Tahun Akademik</span>
                    </label>
                    <select name="tahun_akademik" class="form-control" style="height: 40px;">
                        <option value="" <?= $selectedTahun === '' ? 'selected' : '' ?>>Semua Tahun</option>
                        <?php if ($selectedTahun !== '' && !in_array($selectedTahun, $years, true)): ?>
                            <option value="<?= e($selectedTahun) ?>" selected><?= e($selectedTahun) ?></option>
                        <?php endif; ?>
                        <?php foreach ($years as $year): ?>
                            <option value="<?= e($year) ?>" <?= $selectedTahun === $year ? 'selected' : '' ?>><?= e($year) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; height: 40px;">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-secondary" data-print-page <?= $hasSelectedReport ? '' : 'disabled' ?> style="height: 40px; padding: 0 16px;" title="Cetak Dokumen">
                        <i class="fa-solid fa-print"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger jadwal-print-filter mb-4" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px 16px; border-radius: var(--radius-md);">
        <i class="fa-solid fa-circle-exclamation mr-2"></i> <?= e($error) ?>
    </div>
<?php endif; ?>

<?php if ($hasSelectedReport): ?>
    <!-- TABEL JADWAL SAJA (Clean & Print-Ready) -->
    <div class="jadwal-print-sheet">
        <!-- Header Dokumen Ringkas Tanpa Overlap -->
        <div class="print-doc-header">
            <div>
                <span class="print-doc-sub">SISTEM INFORMASI PENJADWALAN PERKULIAHAN</span>
                <h2 class="print-doc-title">
                    JADWAL MENGAJAR: <?= e($selectedDosen['nama_dosen']) ?><?= $selectedDosen['nidn'] ? ' (NIDN: ' . e($selectedDosen['nidn']) . ')' : '' ?>
                </h2>
            </div>
            <div class="print-doc-meta">
                Semester: <strong><?= e($selectedSemester ?: 'Semua') ?></strong> &bull; 
                TA: <strong><?= e($selectedTahun ?: 'Semua') ?></strong> &bull; 
                Beban: <strong><?= count($items) ?> Sesi Perkuliahan</strong>
            </div>
        </div>

        <?php if (!$items): ?>
            <div class="alert alert-info mb-3" style="background: rgba(var(--primary-rgb), 0.1); border: 1px solid rgba(var(--primary-rgb), 0.25); color: var(--primary); padding: 12px 16px; border-radius: var(--radius-md);">
                <i class="fa-solid fa-circle-info mr-2"></i> Belum ada jadwal mengajar aktif untuk dosen ini pada filter yang dipilih.
            </div>
        <?php endif; ?>

        <!-- Table Container Tanpa Scrollbar di Print -->
        <div class="schedule-table-wrap">
            <table class="table table-bordered print-schedule-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="print-col-jam" style="vertical-align: middle;">JAM / WAKTU</th>
                        <th colspan="<?= count($printDays) ?>" class="print-col-hari-title" style="text-align: center; text-transform: uppercase; letter-spacing: 0.05em;">
                            <i class="fa-regular fa-calendar-days mr-1"></i> NAMA HARI PERKULIAHAN
                        </th>
                    </tr>
                    <tr>
                        <?php foreach ($printDays as $day): ?>
                            <th class="print-col-day">HARI <?= e(strtoupper($day)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jam as $index => $j): ?>
                        <?php $number = jam_number($j, $index + 1); ?>
                        <tr>
                            <th class="print-time">
                                <div>Jam ke-<?= e((string) $number) ?></div>
                                <small><?= e(time_short($j['jam_mulai'])) ?> - <?= e(time_short($j['jam_selesai'])) ?></small>
                            </th>
                            <?php foreach ($printDays as $day): ?>
                                <?php 
                                $cellItems = $matrix[(int) $j['id']][$day] ?? []; 
                                
                                // Grouping jadwal pada sel yang sama (mata kuliah & ruang sama) agar kelas gabungan tidak berulang memakan tinggi
                                $groupedCellItems = [];
                                foreach ($cellItems as $schedule) {
                                    $key = trim($schedule['mata_kuliah']) . '|' . trim($schedule['nama_ruang']);
                                    if (!isset($groupedCellItems[$key])) {
                                        $pList = !empty($schedule['nama_prodi']) ? array_filter(array_map('trim', explode(',', (string) $schedule['nama_prodi']))) : [];
                                        $groupedCellItems[$key] = [
                                            'mata_kuliah' => $schedule['mata_kuliah'],
                                            'nama_ruang'  => $schedule['nama_ruang'],
                                            'classes'     => [$schedule['kode_kelas']],
                                            'prodis'      => $pList,
                                            'total_kelas' => (int) ($schedule['total_kelas'] ?? 1),
                                        ];
                                    } else {
                                        if (!in_array($schedule['kode_kelas'], $groupedCellItems[$key]['classes'], true)) {
                                            $groupedCellItems[$key]['classes'][] = $schedule['kode_kelas'];
                                        }
                                        if (!empty($schedule['nama_prodi'])) {
                                            foreach (explode(',', (string) $schedule['nama_prodi']) as $p) {
                                                $p = trim($p);
                                                if ($p !== '' && !in_array($p, $groupedCellItems[$key]['prodis'], true)) {
                                                    $groupedCellItems[$key]['prodis'][] = $p;
                                                }
                                            }
                                        }
                                        $groupedCellItems[$key]['total_kelas'] = max($groupedCellItems[$key]['total_kelas'], count($groupedCellItems[$key]['classes']));
                                    }
                                }
                                ?>
                                <td>
                                    <?php if (!$groupedCellItems): ?>
                                        <span class="print-empty">-</span>
                                    <?php endif; ?>
                                    <?php foreach ($groupedCellItems as $group): ?>
                                        <div class="print-schedule-entry">
                                            <div class="entry-mk"><?= e($group['mata_kuliah']) ?></div>
                                            <div class="entry-sub">
                                                <span><i class="fa-solid fa-door-open mr-1"></i><?= e($group['nama_ruang']) ?></span>
                                                <span class="entry-dot">&bull;</span>
                                                <span><i class="fa-solid fa-users mr-1"></i><?= e(implode(', ', $group['classes'])) ?></span>
                                            </div>
                                            <?php if (!empty($group['prodis'])): ?>
                                                <div class="entry-prodi" title="<?= e(implode(', ', $group['prodis'])) ?>">
                                                    <i class="fa-solid fa-graduation-cap mr-1"></i><?= e(implode(', ', $group['prodis'])) ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($group['total_kelas'] > 1 || count($group['classes']) > 1): ?>
                                                <div class="entry-badge">
                                                    Gabungan (<?= count($group['classes']) ?> Kelas)
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="print-signature-section">
            <div class="print-date-info">
                Dicetak otomatis dari Sistem Informasi Penjadwalan pada <?= date('d/m/Y H:i') ?> WIB
            </div>
            <div class="print-signature-box">
                <div style="font-size: 0.8rem; color: inherit;">Dosen Pengampu,</div>
                <div class="print-signature-space"></div>
                <div class="print-signature-name"><?= e($selectedDosen['nama_dosen']) ?></div>
                <div class="print-signature-nidn">NIDN: <?= e($selectedDosen['nidn'] ?: '-') ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="card jadwal-print-filter" style="border: 1px solid var(--border); padding: 48px 24px; text-align: center; color: var(--text-muted); width: 100%;">
        <i class="fa-solid fa-chalkboard-user" style="font-size: 2.4rem; opacity: 0.35; margin-bottom: 12px; display: block; color: var(--primary);"></i>
        <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-main);">Pilih Dosen Pengampu</div>
        <p style="margin: 4px 0 0; font-size: 0.85rem; color: var(--text-muted);">Silakan tentukan dosen pengampu pada formulir filter di atas untuk menampilkan dan mencetak lembar jadwal mengajar.</p>
    </div>
<?php endif; ?>
