<?php
class DosenController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->view('dosen/index', ['title' => 'Data Dosen', 'items' => (new Dosen())->all('nama_dosen ASC')]);
    }

    public function create(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->view('dosen/form', ['title' => 'Tambah Dosen', 'item' => null]);
    }

    public function store(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->runAction(fn() => (new Dosen())->create($_POST), 'Data dosen berhasil ditambahkan.', 'dosen');
    }

    public function edit(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->view('dosen/form', ['title' => 'Edit Dosen', 'item' => (new Dosen())->find((int) $_GET['id'])]);
    }

    public function update(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->runAction(fn() => (new Dosen())->update((int) $_POST['id'], $_POST), 'Data dosen berhasil diubah.', 'dosen');
    }

    public function delete(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $this->runAction(fn() => (new Dosen())->delete((int) $_GET['id']), 'Data dosen berhasil dihapus.', 'dosen');
    }
}
