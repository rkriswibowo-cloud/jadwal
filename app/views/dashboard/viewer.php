<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main);">
            <i class="fa-solid fa-door-open" style="color: var(--primary); margin-right: 8px;"></i>Status Penggunaan Ruang Kuliah
        </h2>
        <p style="color: var(--text-muted); margin-top: 4px; font-size: 0.9rem;">
            Informasi ketersediaan ruang, kapasitas, dan jadwal penggunaan ruang secara real-time.
        </p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 18px;">
<?php foreach ($rooms as $room): ?>
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; background: rgba(var(--primary-rgb), 0.08); border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--text-main);">
                <?= e($room['nama_ruang']) ?>
            </h3>
            <span style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: var(--primary); background: var(--surface); padding: 3px 8px; border-radius: 6px; border: 1px solid var(--border);">
                <?= e($room['kode_ruang']) ?>
            </span>
        </div>
        <div style="padding: 18px 20px; flex: 1; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; font-size: 0.86rem; color: var(--text-muted); margin-bottom: 8px;">
                <span>Kapasitas: <strong style="color: var(--text-main);"><?= e((string)$room['kapasitas']) ?> Kursi</strong></span>
                <span>Lokasi: <strong style="color: var(--text-main);"><?= e($room['lokasi']) ?></strong></span>
            </div>
            
            <div style="margin-top: 6px; margin-bottom: 14px;">
                <?php if (!empty($room['jadwal'])): ?>
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; font-size: 0.76rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;">
                        <i class="fa-solid fa-circle-check"></i> Sedang Digunakan (<?= count($room['jadwal']) ?> Sesi)
                    </span>
                <?php else: ?>
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(148, 163, 184, 0.12); border: 1px solid var(--border); color: var(--text-muted); font-size: 0.76rem; font-weight: 600; padding: 3px 10px; border-radius: 20px;">
                        <i class="fa-regular fa-circle"></i> Ruang Kosong / Tersedia
                    </span>
                <?php endif; ?>
            </div>

            <div style="margin-top: auto; padding: 10px 12px; background: var(--surface-hover); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.8rem; min-height: 48px; display: flex; align-items: center;">
                <?php if (!empty($room['jadwal'])): ?>
                    <div style="overflow-x: auto; white-space: nowrap; width: 100%; color: var(--text-main);">
                        <?php foreach ($room['jadwal'] as $j): ?>
                            <span style="margin-right: 14px;">
                                <strong style="color: var(--primary);"><?= e($j['hari']) ?></strong> (<?= e(time_short($j['jam_mulai'])) ?>-<?= e(time_short($j['jam_selesai'])) ?>): 
                                <?= e($j['mata_kuliah']) ?> &bull; <small style="color: var(--text-muted);"><?= e($j['kode_kelas']) ?></small>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <span style="color: var(--text-muted); font-style: italic;">Tidak ada perkuliahan aktif di ruang ini.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
