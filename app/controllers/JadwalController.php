<?php
class JadwalController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $jadwal = new Jadwal();
        $filters = [
            'hari' => trim((string) ($_GET['hari'] ?? '')),
            'ruang_id' => (int) ($_GET['ruang_id'] ?? 0),
            'prodi_id' => (int) ($_GET['prodi_id'] ?? 0),
            'tahun_akademik' => trim((string) ($_GET['tahun_akademik'] ?? '')),
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];

        $this->view('jadwal/index', [
            'title' => 'Data Jadwal',
            'items' => $jadwal->allDetail($filters),
            'filters' => $filters,
            'days' => academic_days(),
            'ruang' => (new Ruang())->all('nama_ruang ASC'),
            'prodi' => (new Prodi())->all('nama_prodi ASC'),
            'years' => $jadwal->academicYears(),
        ]);
    }

    public function create(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->form('Tambah Jadwal', null);
    }

    public function edit(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->form('Edit Jadwal', (new Jadwal())->findWithClasses((int) $_GET['id']));
    }

    private function form(string $title, ?array $item): void
    {
        $jadwal = new Jadwal();
        $this->view('jadwal/form', [
            'title' => $title,
            'item' => $item,
            'ruang' => (new Ruang())->all('nama_ruang ASC'),
            'kelas' => (new Kelas())->allWithProdi(),
            'jam' => (new JamPemakaian())->all('jam_mulai ASC'),
            'mataKuliah' => (new MataKuliah())->activeOptions(),
            'dosen' => (new Dosen())->activeOptions(),
            'days' => academic_days(),
            'scheduleGrid' => $jadwal->allDetail(),
            'periods' => (new AcademicPeriod())->catalog(),
        ]);
    }

    public function store(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->runAction(fn() => (new Jadwal())->create($this->withAcademicPeriod($_POST)), 'Jadwal berhasil ditambahkan.', 'jadwal');
    }

    public function update(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->runAction(fn() => (new Jadwal())->update((int) $_POST['id'], $this->withAcademicPeriod($_POST)), 'Jadwal berhasil diubah.', 'jadwal');
    }

    private function withAcademicPeriod(array $data): array
    {
        $periods = (new AcademicPeriod())->catalog();
        if (!$periods || !$periods[0]['is_active']) {
            return $data;
        }
        $selection = $data['academic_period'] ?? null;
        if ($selection === null && is_string($data['tahun_akademik'] ?? null) && is_string($data['semester_akademik'] ?? null)) {
            $selection = $data['tahun_akademik'] . ' ' . $data['semester_akademik'];
        }
        foreach ($periods as $period) {
            if ($selection === $period['academic_year'] . ' ' . $period['semester']) {
                $data['tahun_akademik'] = $period['academic_year'];
                $data['semester_akademik'] = $period['semester'];
                return $data;
            }
        }
        throw new RuntimeException('Pilih periode akademik yang tersedia.');
    }

    public function delete(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $this->runAction(fn() => (new Jadwal())->delete((int) $_GET['id']), 'Jadwal berhasil dihapus.', 'jadwal');
    }

    public function cetak(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);

        $jadwal = new Jadwal();
        $kelasModel = new Kelas();
        $years = $jadwal->academicYears();
        $selectedProdiId = (int) ($_GET['prodi_id'] ?? 0);
        $selectedKelasId = (int) ($_GET['kelas_id'] ?? 0);
        $selectedSemester = (string) ($_GET['semester_akademik'] ?? '');
        $selectedTahun = trim((string) ($_GET['tahun_akademik'] ?? ($years[0] ?? '')));
        $selectedKelas = null;
        $items = [];
        $error = null;

        if ($selectedKelasId > 0) {
            $selectedKelas = $kelasModel->findWithProdi($selectedKelasId);

            if (!$selectedKelas) {
                $error = 'Kelas tidak ditemukan.';
            } elseif ($selectedProdiId > 0 && (int) $selectedKelas['prodi_id'] !== $selectedProdiId) {
                $selectedKelas = null;
                $error = 'Kelas yang dipilih tidak sesuai dengan prodi.';
            } else {
                $items = $jadwal->scheduleForClass($selectedKelasId, $selectedSemester, $selectedTahun);
            }
        }

        $this->view('jadwal/cetak', [
            'title' => 'Cetak Jadwal',
            'prodi' => (new Prodi())->all('nama_prodi ASC'),
            'kelas' => $kelasModel->allWithProdi(),
            'jam' => (new JamPemakaian())->all('jam_mulai ASC'),
            'days' => academic_days(),
            'years' => $years,
            'items' => $items,
            'selectedProdiId' => $selectedProdiId,
            'selectedKelasId' => $selectedKelasId,
            'selectedSemester' => $selectedSemester,
            'selectedTahun' => $selectedTahun,
            'selectedKelas' => $selectedKelas,
            'error' => $error,
        ]);
    }

    public function cetakDosen(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);

        $jadwal = new Jadwal();
        $dosenModel = new Dosen();
        $years = $jadwal->academicYears();
        $selectedDosenId = (int) ($_GET['dosen_id'] ?? 0);
        $selectedSemester = (string) ($_GET['semester_akademik'] ?? '');
        $selectedTahun = trim((string) ($_GET['tahun_akademik'] ?? ($years[0] ?? '')));
        $selectedDosen = null;
        $items = [];
        $error = null;

        if ($selectedDosenId > 0) {
            $selectedDosen = $dosenModel->find($selectedDosenId);

            if (!$selectedDosen) {
                $error = 'Dosen tidak ditemukan.';
            } else {
                $items = $jadwal->scheduleForDosen($selectedDosen['nama_dosen'], $selectedSemester, $selectedTahun);
            }
        } elseif (!empty($_GET['dosen'])) {
            $dosenName = trim((string) $_GET['dosen']);
            $allDosen = $dosenModel->activeOptions();
            foreach ($allDosen as $d) {
                if (strcasecmp($d['nama_dosen'], $dosenName) === 0) {
                    $selectedDosen = $d;
                    $selectedDosenId = (int) $d['id'];
                    break;
                }
            }
            if (!$selectedDosen) {
                $selectedDosen = [
                    'id' => 0,
                    'nidn' => '-',
                    'nama_dosen' => $dosenName,
                    'email' => '-',
                ];
            }
            $items = $jadwal->scheduleForDosen($dosenName, $selectedSemester, $selectedTahun);
        }

        $this->view('jadwal/cetak_dosen', [
            'title' => 'Cetak Jadwal Dosen',
            'dosenList' => $dosenModel->activeOptions(),
            'jam' => (new JamPemakaian())->all('jam_mulai ASC'),
            'days' => academic_days(),
            'years' => $years,
            'items' => $items,
            'selectedDosenId' => $selectedDosenId,
            'selectedSemester' => $selectedSemester,
            'selectedTahun' => $selectedTahun,
            'selectedDosen' => $selectedDosen,
            'error' => $error,
        ]);
    }
}
