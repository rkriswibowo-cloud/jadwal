<?php
class PenggunaController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $this->view('pengguna/index', ['title' => 'Manajemen Pengguna', 'items' => (new User())->listWithRole()]);
    }

    public function create(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $this->form(null);
    }

    public function store(): void
    {
        AuthMiddleware::requireRole(['admin']);
        require_account_csrf();
        $this->save(null);
    }

    public function edit(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $id = (int)filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === (int)user()['id']) {
            redirect('profil');
        }
        $account = (new User())->findAccount($id);
        if (!$account) {
            flash('error', 'Pengguna tidak ditemukan.');
            redirect('pengguna');
        }
        $this->form($account);
    }

    public function update(): void
    {
        AuthMiddleware::requireRole(['admin']);
        require_account_csrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            http_response_code(400);
            exit('ID pengguna tidak valid.');
        }
        $this->save($id);
    }

    public function delete(): void
    {
        AuthMiddleware::requireRole(['admin']);
        require_account_csrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            http_response_code(400);
            exit('ID pengguna tidak valid.');
        }
        $this->runAction(
            fn() => (new User())->deleteManagedAccount((int)user()['id'], $id),
            'Pengguna berhasil dihapus.',
            'pengguna'
        );
    }

    private function save(?int $id): void
    {
        try {
            (new User())->saveManagedAccount((int)user()['id'], $id, $_POST);
        } catch (PDOException $exception) {
            $message = ($exception->errorInfo[0] ?? '') === '23000'
                ? 'Email sudah digunakan atau data terkait tidak tersedia.'
                : 'Data pengguna gagal disimpan.';
        } catch (RuntimeException $exception) {
            $message = $exception->getMessage();
        }
        if (isset($message)) {
            http_response_code(422);
            flash('error', $message);
            $account = $id === null ? null : (new User())->findAccount($id);
            if ($id !== null && (!$account || $id === (int)user()['id'])) {
                redirect('pengguna');
            }
            $this->form($account);
            return;
        }
        flash('success', $id === null ? 'Pengguna berhasil ditambahkan.' : 'Pengguna berhasil diperbarui.');
        redirect('pengguna');
    }

    private function form(?array $account): void
    {
        $this->view('pengguna/form', [
            'title' => $account ? 'Edit Pengguna' : 'Tambah Pengguna',
            'account' => $account,
            'roles' => (new User())->roles(),
            'programs' => (new Prodi())->all('nama_prodi ASC'),
        ]);
    }
}
