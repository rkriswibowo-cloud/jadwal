<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $rooms = (new Ruang())->all('nama_ruang ASC');
        $schedules = (new Jadwal())->allDetail();

        $this->viewPublic('home/index', [
            'title' => config_app('name'),
            'cards' => [
                [
                    'label' => 'Ruang',
                    'value' => count($rooms),
                    'icon' => 'door-open',
                    'class' => 'primary',
                ],
                [
                    'label' => 'Prodi',
                    'value' => count((new Prodi())->all()),
                    'icon' => 'university',
                    'class' => 'success',
                ],
                [
                    'label' => 'Kelas',
                    'value' => count((new Kelas())->all()),
                    'icon' => 'users',
                    'class' => 'warning',
                ],
                [
                    'label' => 'Jam',
                    'value' => count((new JamPemakaian())->all()),
                    'icon' => 'clock',
                    'class' => 'indigo',
                ],
                [
                    'label' => 'Jadwal Aktif',
                    'value' => count(array_filter($schedules, fn($item) => ($item['status'] ?? '') === 'aktif')),
                    'icon' => 'calendar-check',
                    'class' => 'danger',
                ],
            ],
            'rooms' => array_slice((new Jadwal())->viewerCards(), 0, 5),
        ]);
    }
}
