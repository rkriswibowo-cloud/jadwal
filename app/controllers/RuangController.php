<?php
class RuangController extends Controller
{
    public function index(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->view('ruang/index', ['title'=>'Data Ruang','items'=>(new Ruang())->all('nama_ruang ASC')]); }
    public function create(): void { AuthMiddleware::requireRole(['admin']); $this->view('ruang/form', ['title'=>'Tambah Ruang','item'=>null]); }
    public function store(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Ruang())->create($_POST), 'Data ruang berhasil ditambahkan.', 'ruang'); }
    public function edit(): void { AuthMiddleware::requireRole(['admin']); $this->view('ruang/form', ['title'=>'Edit Ruang','item'=>(new Ruang())->find((int)$_GET['id'])]); }
    public function update(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Ruang())->update((int)$_POST['id'], $_POST), 'Data ruang berhasil diubah.', 'ruang'); }
    public function delete(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new Ruang())->delete((int)$_GET['id']), 'Data ruang berhasil dihapus.', 'ruang'); }
}
