<?php
class MataKuliahController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->view('mata_kuliah/index', ['title' => 'Data Mata Kuliah', 'items' => (new MataKuliah())->all('nama_mk ASC')]);
    }

    public function create(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->view('mata_kuliah/form', ['title' => 'Tambah Mata Kuliah', 'item' => null]);
    }

    public function store(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->runAction(fn() => (new MataKuliah())->create($_POST), 'Data mata kuliah berhasil ditambahkan.', 'mata-kuliah');
    }

    public function edit(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->view('mata_kuliah/form', ['title' => 'Edit Mata Kuliah', 'item' => (new MataKuliah())->find((int) $_GET['id'])]);
    }

    public function update(): void
    {
        AuthMiddleware::requireRole(['admin', 'operator_prodi']);
        $this->runAction(fn() => (new MataKuliah())->update((int) $_POST['id'], $_POST), 'Data mata kuliah berhasil diubah.', 'mata-kuliah');
    }

    public function delete(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $this->runAction(fn() => (new MataKuliah())->delete((int) $_GET['id']), 'Data mata kuliah berhasil dihapus.', 'mata-kuliah');
    }
}
