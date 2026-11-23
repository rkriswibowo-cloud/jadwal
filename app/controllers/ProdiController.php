<?php
class ProdiController extends Controller
{
    public function index(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->view('prodi/index', ['title'=>'Data Prodi','items'=>(new Prodi())->all('nama_prodi ASC')]); }
    public function create(): void { AuthMiddleware::requireRole(['admin']); $this->view('prodi/form', ['title'=>'Tambah Prodi','item'=>null]); }
    public function store(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Prodi())->create($_POST), 'Data prodi berhasil ditambahkan.', 'prodi'); }
    public function edit(): void { AuthMiddleware::requireRole(['admin']); $this->view('prodi/form', ['title'=>'Edit Prodi','item'=>(new Prodi())->find((int)$_GET['id'])]); }
    public function update(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Prodi())->update((int)$_POST['id'], $_POST), 'Data prodi berhasil diubah.', 'prodi'); }
    public function delete(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Prodi())->delete((int)$_GET['id']), 'Data prodi berhasil dihapus.', 'prodi'); }
}
