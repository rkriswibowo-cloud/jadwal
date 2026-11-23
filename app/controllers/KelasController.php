<?php
class KelasController extends Controller
{
    public function index(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->view('kelas/index', ['title'=>'Data Kelas','items'=>(new Kelas())->allWithProdi()]); }
    public function create(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->view('kelas/form', ['title'=>'Tambah Kelas','item'=>null,'prodi'=>(new Prodi())->all('nama_prodi ASC')]); }
    public function store(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->runAction(fn() => (new Kelas())->create($_POST), 'Data kelas berhasil ditambahkan.', 'kelas'); }
    public function edit(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->view('kelas/form', ['title'=>'Edit Kelas','item'=>(new Kelas())->find((int)$_GET['id']),'prodi'=>(new Prodi())->all('nama_prodi ASC')]); }
    public function update(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->runAction(fn() => (new Kelas())->update((int)$_POST['id'], $_POST), 'Data kelas berhasil diubah.', 'kelas'); }
    public function delete(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Kelas())->delete((int)$_GET['id']), 'Data kelas berhasil dihapus.', 'kelas'); }
}
