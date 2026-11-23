<?php
$selectedSlotCode = $item['kode_jam'] ?? ($standardSlots[0]['code'] ?? 'JAM1');
$selectedSlot = $standardSlots[0] ?? null;
foreach ($standardSlots as $slot) {
    if ($slot['code'] === $selectedSlotCode) {
        $selectedSlot = $slot;
        break;
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main); letter-spacing: -0.02em;">
            <?= $item ? 'Edit Slot Jam Kuliah' : 'Tambah Slot Jam Kuliah Baru' ?>
        </h2>
        <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 0.88rem;">
            <?= $item ? 'Perbarui informasi rentang waktu atau keterangan sesi perkuliahan.' : 'Pilih slot jam perkuliahan standar dari katalog akademik kampus untuk pemetaan jadwal.' ?>
        </p>
    </div>
    <a href="<?= url('jam') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Master Jam Kuliah
    </a>
</div>

<div class="card" style="width: 100%;">
    <form method="post" action="<?= $item ? url('jam/update') : url('jam/store') ?>" data-confirm-submit>
        <div class="form-card-body">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= e($item['id']) ?>">
            <?php endif; ?>
            <input type="hidden" name="kode_jam" data-slot-code value="<?= e($selectedSlot['code'] ?? '') ?>">
            <input type="hidden" name="jam_mulai" data-slot-start value="<?= e($selectedSlot['start_db'] ?? '') ?>">
            <input type="hidden" name="jam_selesai" data-slot-end value="<?= e($selectedSlot['end_db'] ?? '') ?>">

            <div class="form-grid-2" style="margin-bottom: 20px; align-items: start;">
                <div>
                    <label class="form-label">
                        <span><i class="fa-regular fa-clock mr-1 text-primary"></i> Sesi Jam Perkuliahan Standar <span class="required">*</span></span>
                    </label>
                    <select name="slot_code" class="form-control" data-slot-select required style="height: 44px;">
                        <?php foreach ($standardSlots as $slot): ?>
                            <option
                                value="<?= e($slot['code']) ?>"
                                data-code="<?= e($slot['code']) ?>"
                                data-start="<?= e($slot['start_db']) ?>"
                                data-end="<?= e($slot['end_db']) ?>"
                                data-label="<?= e($slot['label']) ?>"
                                <?= $selectedSlotCode === $slot['code'] ? 'selected' : '' ?>
                            >
                                <?= e($slot['label']) ?> | <?= e($slot['start']) ?> - <?= e($slot['end']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-hint">Pilih dari katalog standar akademik kampus.</span>
                </div>

                <div>
                    <label class="form-label">
                        <span><i class="fa-solid fa-stopwatch mr-1 text-primary"></i> Rentang Waktu Terpilih</span>
                    </label>
                    <div class="slot-preview" style="height: 44px; display: flex; align-items: center; padding: 0 16px; margin-bottom: 0;">
                        <i class="fa-regular fa-clock" style="font-size: 1.25rem;"></i>
                        <div>
                            <div style="font-size: 1.05rem; font-weight: 700; font-family: monospace; line-height: 1.2;" data-slot-preview>
                                <?= e(($selectedSlot['start'] ?? '') . ' - ' . ($selectedSlot['end'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                    <span class="form-hint">Format jam 24 jam (WIB).</span>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">
                    <span><i class="fa-solid fa-note-sticky mr-1 text-primary"></i> Keterangan Sesi / Catatan</span>
                </label>
                <input name="keterangan" class="form-control" value="<?= e($item['keterangan'] ?? ($selectedSlot['description'] ?? '')) ?>" placeholder="Misal: Kuliah Pagi / Istirahat Siang / Kuliah Sore">
                <span class="form-hint">Keterangan tambahan untuk sesi perkuliahan ini.</span>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="<?= url('jam') ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Simpan Perubahan' : 'Simpan Slot Jam' ?>
            </button>
        </div>
    </form>
</div>
