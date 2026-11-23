<header style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;margin-bottom:24px">
    <div>
        <h1 style="margin:0;font-size:1.5rem">Periode Akademik</h1>
        <p style="margin:4px 0 0;color:var(--text-muted)">Periode aktif OBE menjadi pilihan awal untuk jadwal baru.</p>
    </div>
    <form method="post" action="<?= e(url('periode-akademik/sinkronkan')) ?>" data-confirm-submit
          data-confirm-title="Sinkronkan periode dari OBE?">
        <input type="hidden" name="csrf_token" value="<?= e(account_csrf_token()) ?>">
        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-rotate" aria-hidden="true"></i> Sinkronkan dari OBE</button>
    </form>
</header>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Tahun Akademik</th><th>Semester</th><th>Sumber</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($items as $period): ?>
                    <tr>
                        <td><?= e($period['academic_year']) ?></td>
                        <td><?= e($period['semester']) ?></td>
                        <td><?= $period['from_obe'] ? 'OBE' : 'Jadwal lama' ?></td>
                        <td><?= $period['is_active'] ? 'Aktif' : '—' ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$items): ?><tr><td colspan="4">Belum ada periode akademik.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
