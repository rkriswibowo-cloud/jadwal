<?php
class DashboardController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $role = user()['role'];
        if ($role === 'viewer') {
            $rooms = (new Jadwal())->viewerCards();
            $this->view('dashboard/viewer', ['title' => 'Dashboard Viewer', 'rooms' => $rooms]);
            return;
        }
        $jadwal = (new Jadwal())->allDetail();
        $byDay = array_fill_keys(academic_days(), 0);
        $byRoom = [];

        foreach ($jadwal as $item) {
            if (($item['status'] ?? '') !== 'aktif') {
                continue;
            }

            if (isset($byDay[$item['hari']])) {
                $byDay[$item['hari']]++;
            }

            $roomName = $item['nama_ruang'] ?? 'Ruang';
            $byRoom[$roomName] = ($byRoom[$roomName] ?? 0) + 1;
        }

        arsort($byRoom);

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'totalRuang' => count((new Ruang())->all()),
            'totalProdi' => count((new Prodi())->all()),
            'totalKelas' => count((new Kelas())->all()),
            'totalJadwal' => count($jadwal),
            'dashboardCharts' => [
                'byDay' => [
                    'labels' => array_keys($byDay),
                    'values' => array_values($byDay),
                ],
                'byRoom' => [
                    'labels' => array_keys($byRoom),
                    'values' => array_values($byRoom),
                ],
            ],
        ]);
    }
}
