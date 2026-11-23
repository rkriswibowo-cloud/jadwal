<?php
$chartPayload = json_encode($dashboardCharts ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>
<div style="display: flex; flex-direction: column; gap: 24px;">

    <!-- Welcome & Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-main);">
                <i class="fa-solid fa-calendar-check" style="color: var(--primary); margin-right: 8px;"></i>Dashboard Penjadwalan Ruang
            </h2>
            <p style="color: var(--text-muted); margin: 6px 0 0 0; font-size: 0.9rem;">
                Monitoring pemakaian ruang kuliah, sebaran jadwal per hari, dan manajemen data akademik.
            </p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= url('jadwal') ?>" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; padding: 9px 16px;">
                <i class="fa-solid fa-calendar-days"></i> Kelola Jadwal
            </a>
            <a href="<?= url('jadwal/cetak') ?>" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; padding: 9px 16px;">
                <i class="fa-solid fa-print"></i> Cetak Matriks
            </a>
        </div>
    </div>

    <!-- KPI Summary Grid (OBE Style) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">
        
        <!-- 1. Total Ruang -->
        <div style="background: var(--surface); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Total Ruang Kuliah</div>
                <div style="font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-top: 4px; line-height: 1;">
                    <?= e((string)$totalRuang) ?>
                </div>
                <div style="color: var(--text-muted); font-size: 0.78rem; margin-top: 6px;">Kapasitas Ruang Terdaftar</div>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(var(--primary-rgb), 0.12); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.4rem;">
                <i class="fa-solid fa-door-open"></i>
            </div>
        </div>

        <!-- 2. Program Studi -->
        <div style="background: var(--surface); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Program Studi</div>
                <div style="font-size: 2.2rem; font-weight: 800; color: var(--success); margin-top: 4px; line-height: 1;">
                    <?= e((string)$totalProdi) ?>
                </div>
                <div style="color: var(--text-muted); font-size: 0.78rem; margin-top: 6px;">Jurusan / Program Studi</div>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; color: var(--success); font-size: 1.4rem;">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>

        <!-- 3. Total Kelas -->
        <div style="background: var(--surface); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Kelas / Rombel</div>
                <div style="font-size: 2.2rem; font-weight: 800; color: var(--warning); margin-top: 4px; line-height: 1;">
                    <?= e((string)$totalKelas) ?>
                </div>
                <div style="color: var(--text-muted); font-size: 0.78rem; margin-top: 6px;">Kelompok Belajar Aktif</div>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); display: flex; align-items: center; justify-content: center; color: var(--warning); font-size: 1.4rem;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- 4. Total Jadwal Aktif -->
        <div style="background: var(--surface); padding: 22px; border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Sesi Jadwal Terplot</div>
                <div style="font-size: 2.2rem; font-weight: 800; color: var(--danger); margin-top: 4px; line-height: 1;">
                    <?= e((string)$totalJadwal) ?>
                </div>
                <div style="color: var(--text-muted); font-size: 0.78rem; margin-top: 6px;">Slot Perkuliahan Terjadwal</div>
            </div>
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(239, 68, 68, 0.12); display: flex; align-items: center; justify-content: center; color: var(--danger); font-size: 1.4rem;">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>
    </div>

    <!-- Charts Section (Preserving data-dashboard-charts and chart canvas IDs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 18px;" data-dashboard-charts='<?= e($chartPayload) ?>'>
        
        <!-- Chart 1: Jadwal Aktif per Hari -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-chart-column" style="color: var(--primary);"></i>
                    <h3 style="margin: 0; font-size: 0.95rem; font-weight: 600; color: var(--text-main);">Distribusi Jadwal per Hari</h3>
                </div>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Senin - Minggu</span>
            </div>
            <div style="padding: 18px;">
                <div id="scheduleByDayChart" class="dashboard-apex-chart"></div>
            </div>
        </div>

        <!-- Chart 2: Penggunaan Ruang -->
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm);">
            <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-chart-pie" style="color: var(--secondary);"></i>
                    <h3 style="margin: 0; font-size: 0.95rem; font-weight: 600; color: var(--text-main);">Persentase Penggunaan Ruang</h3>
                </div>
                <span style="font-size: 0.78rem; color: var(--text-muted);">Slot Terpakai</span>
            </div>
            <div style="padding: 18px;">
                <div id="roomUsageChart" class="dashboard-apex-chart dashboard-apex-chart-donut"></div>
            </div>
        </div>
    </div>
</div>
