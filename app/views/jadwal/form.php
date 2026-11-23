<?php
$days = $days ?? academic_days();
$selectedRoomId = (string) ($item['ruang_id'] ?? ($ruang[0]['id'] ?? ''));
$selectedJamId = (string) ($item['jam_id'] ?? ($jam[0]['id'] ?? ''));
$selectedDay = $item['hari'] ?? $days[0];
$selectedMataKuliah = (string) ($item['mata_kuliah'] ?? '');
$selectedDosen = (string) ($item['dosen'] ?? '');
$selectedClassIds = array_map('intval', (array) ($item['kelas_ids'] ?? ($item['kelas_id'] ?? [])));
if (!$selectedClassIds && !empty($kelas[0]['id'])) {
    $selectedClassIds = [(int) $kelas[0]['id']];
}
$editId = (int) ($item['id'] ?? 0);
$mataKuliahNames = array_column($mataKuliah ?? [], 'nama_mk');
$dosenNames = array_column($dosen ?? [], 'nama_dosen');
$schedulePayload = array_map(static function (array $row): array {
    return [
        'id' => (int) $row['id'],
        'ruang_id' => (int) $row['ruang_id'],
        'jam_id' => (int) $row['jam_id'],
        'hari' => $row['hari'],
        'mata_kuliah' => $row['mata_kuliah'],
        'dosen' => $row['dosen'],
        'kelas' => $row['kode_kelas'],
        'kelas_ids' => array_map('intval', array_filter(explode(',', (string) ($row['kelas_ids'] ?? '')))),
        'prodi' => $row['nama_prodi'],
    ];
}, $scheduleGrid ?? []);
?>

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Plotting Jadwal Kuliah' : 'Tambah Plotting Jadwal Kuliah' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Sesuaikan penempatan ruang, hari, jam, atau kelas gabungan.' : 'Tentukan slot ruang, hari, sesi jam, serta deteksi bentrok jadwal secara real-time.' ?>
        </p>
    </div>
    <a href="<?= url('jadwal') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Jadwal
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('jadwal/update') : url('jadwal/store') ?>" data-confirm-submit data-schedule-form>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>

            <!-- Baris 1: Ruang & Sesi Jam -->
            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-door-open mr-1 text-primary"></i> Ruang Kuliah <span class="required">*</span></span>
                    </label>
                    <select name="ruang_id" class="form-control" data-room-select required style="height: 42px;">
                        <?php foreach ($ruang as $r): ?>
                            <option value="<?= e($r['id']) ?>" <?= $selectedRoomId === (string) $r['id'] ? 'selected' : '' ?>>
                                <?= e($r['nama_ruang']) ?> (Kapasitas: <?= e($r['kapasitas']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint">Ruang perkuliahan fisik tempat belajar berlangsung.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-regular fa-clock mr-1 text-primary"></i> Sesi Jam Kuliah <span class="required">*</span></span>
                    </label>
                    <select name="jam_id" class="form-control" data-jam-select required style="height: 42px;">
                        <?php foreach ($jam as $index => $j): ?>
                            <?php $number = jam_number($j, $index + 1); ?>
                            <option value="<?= e($j['id']) ?>" <?= $selectedJamId === (string) $j['id'] ? 'selected' : '' ?>>
                                Jam ke-<?= e($number) ?> | <?= e(time_short($j['jam_mulai'])) ?> - <?= e(time_short($j['jam_selesai'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint">Interval jam sesi perkuliahan standar.</span>
                </div>
            </div>

            <!-- Baris 2: Hari, Semester, Tahun, Status -->
            <div class="form-grid-4" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-regular fa-calendar-days mr-1 text-primary"></i> Hari Kuliah</span>
                    </label>
                    <select name="hari" class="form-control" data-day-select style="height: 42px;">
                        <?php foreach ($days as $day): ?>
                            <option value="<?= e($day) ?>" <?= $selectedDay === $day ? 'selected' : '' ?>><?= e($day) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-layer-group mr-1 text-primary"></i> Semester</span>
                    </label>
                    <select name="semester_akademik" class="form-control" style="height: 42px;">
                        <option value="Ganjil" <?= (($item['semester_akademik'] ?? '') === 'Ganjil') ? 'selected' : '' ?>>Ganjil</option>
                        <option value="Genap" <?= (($item['semester_akademik'] ?? '') === 'Genap') ? 'selected' : '' ?>>Genap</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-regular fa-calendar mr-1 text-primary"></i> Tahun Akademik</span>
                    </label>
                    <input name="tahun_akademik" class="form-control" value="<?= e($item['tahun_akademik'] ?? '2025/2026') ?>" placeholder="2025/2026" style="height: 42px;">
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-toggle-on mr-1 text-primary"></i> Status Jadwal</span>
                    </label>
                    <select name="status" class="form-control" style="height: 42px;">
                        <option value="aktif" <?= (($item['status'] ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif (Terpublikasi)</option>
                        <option value="nonaktif" <?= (($item['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Baris 3: Mata Kuliah & Dosen -->
            <div class="form-grid-2" style="margin-bottom: 20px;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-book-open mr-1 text-primary"></i> Mata Kuliah <span class="required">*</span></span>
                    </label>
                    <select name="mata_kuliah" class="form-control" required style="height: 42px;">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        <?php foreach ($mataKuliah as $mk): ?>
                            <option value="<?= e($mk['nama_mk']) ?>" <?= $selectedMataKuliah === $mk['nama_mk'] ? 'selected' : '' ?>>
                                <?= e($mk['kode_mk'] . ' - ' . $mk['nama_mk']) ?><?= (int) $mk['sks'] > 0 ? ' (' . e($mk['sks']) . ' SKS)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if ($selectedMataKuliah !== '' && !in_array($selectedMataKuliah, $mataKuliahNames, true)): ?>
                            <option value="<?= e($selectedMataKuliah) ?>" selected><?= e($selectedMataKuliah) ?></option>
                        <?php endif; ?>
                    </select>
                    <span class="form-hint">Mata kuliah yang dijadwalkan pada slot ini.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-chalkboard-user mr-1 text-primary"></i> Dosen Pengampu</span>
                    </label>
                    <select name="dosen" class="form-control" style="height: 42px;">
                        <option value="">-- Pilih Dosen Pengampu --</option>
                        <?php foreach ($dosen as $d): ?>
                            <option value="<?= e($d['nama_dosen']) ?>" <?= $selectedDosen === $d['nama_dosen'] ? 'selected' : '' ?>>
                                <?= e(trim(($d['nidn'] ? $d['nidn'] . ' - ' : '') . $d['nama_dosen'])) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if ($selectedDosen !== '' && !in_array($selectedDosen, $dosenNames, true)): ?>
                            <option value="<?= e($selectedDosen) ?>" selected><?= e($selectedDosen) ?></option>
                        <?php endif; ?>
                    </select>
                    <span class="form-hint">Dosen yang ditugaskan mengajar sesi ini.</span>
                </div>
            </div>

            <!-- Baris 4: Kelas Rombel (Bisa Multiple) -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">
                    <span><i class="fa-solid fa-users mr-1 text-primary"></i> Rombongan Belajar / Kelas <span class="required">*</span></span>
                    <span style="font-size: 0.76rem; color: var(--text-muted); font-weight: normal;">(Tahan Ctrl / Cmd untuk memilih lebih dari 1 kelas gabungan)</span>
                </label>
                <select name="kelas_ids[]" class="form-control" size="4" multiple required style="border-radius: var(--radius-sm); min-height: 110px;">
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= e($k['id']) ?>" <?= in_array((int) $k['id'], $selectedClassIds, true) ? 'selected' : '' ?>>
                            <?= e($k['kode_kelas'] . ' - ' . $k['nama_kelas'] . ' (' . $k['nama_prodi'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="form-hint">Pilih satu atau lebih rombongan belajar jika perkuliahan merupakan kelas gabungan.</span>
            </div>

            <!-- Matriks Visual Real-time Conflict Detector -->
            <div class="schedule-grid-panel" data-schedule-grid data-edit-id="<?= e($editId) ?>" data-schedules='<?= e(json_encode($schedulePayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP)) ?>'>
                <div class="schedule-grid-title">
                    <div>
                        <h3 style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-table-cells text-primary"></i>
                            <span>Matriks Ketersediaan Slot Ruangan</span>
                        </h3>
                        <p>
                            Sistem secara instan menampilkan slot kosong dan memvalidasi potensi bentrok jadwal di ruang terpilih.
                        </p>
                    </div>
                    <span class="badge-status-active">
                        <i class="fa-solid fa-circle-check"></i> Deteksi Konflik Aktif
                    </span>
                </div>

                <div class="alert alert-warning d-none" data-conflict-alert style="margin: 16px 20px; padding: 12px 16px; border-radius: var(--radius-sm); border: 1px solid rgba(245, 158, 11, 0.4); background: rgba(245, 158, 11, 0.1); color: var(--warning); font-size: 0.88rem;"></div>

                <div class="table-responsive" style="margin: 0; border: none; border-radius: 0;">
                    <table class="table schedule-matrix mb-0">
                        <thead>
                            <tr>
                                <th style="width: 130px; padding: 12px 14px;">SESI JAM</th>
                                <?php foreach ($days as $day): ?>
                                    <th style="padding: 12px 14px; text-align: center;"><?= e($day) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jam as $index => $j): ?>
                                <?php $number = jam_number($j, $index + 1); ?>
                                <tr>
                                    <th class="schedule-time" style="padding: 10px 14px;">
                                        <div style="font-weight: 700; color: var(--text-main);">Jam ke-<?= e($number) ?></div>
                                        <small style="font-size: 0.72rem; color: var(--text-muted); font-family: monospace; margin-top: 2px;">
                                            <?= e(time_short($j['jam_mulai'])) ?> - <?= e(time_short($j['jam_selesai'])) ?>
                                        </small>
                                    </th>
                                    <?php foreach ($days as $day): ?>
                                        <td data-day="<?= e($day) ?>" data-jam-id="<?= e($j['id']) ?>" style="padding: 8px 10px; border-left: 1px solid var(--border); vertical-align: top; text-align: center; min-width: 110px;">
                                            <span class="slot-empty">
                                                <i class="fa-regular fa-circle" style="font-size: 0.65rem; margin-right: 4px;"></i> Kosong
                                            </span>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('jadwal') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan Jadwal' : 'Simpan Plotting Jadwal' ?>
            </button>
        </div>
    </form>
</div>
