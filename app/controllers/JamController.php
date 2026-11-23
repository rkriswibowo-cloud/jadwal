<?php
class JamController extends Controller
{
    public function index(): void { AuthMiddleware::requireRole(['admin','operator_prodi']); $this->view('jam/index', ['title'=>'Data Jam Pemakaian','items'=>(new JamPemakaian())->all('jam_mulai ASC')]); }
    public function create(): void { AuthMiddleware::requireRole(['admin']); $model = new JamPemakaian(); $this->view('jam/form', ['title'=>'Tambah Jam Pemakaian','item'=>null,'standardSlots'=>$model->slotOptions()]); }
    public function store(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new JamPemakaian())->create($_POST), 'Data jam berhasil ditambahkan.', 'jam'); }
    public function edit(): void { AuthMiddleware::requireRole(['admin']); $model = new JamPemakaian(); $item = $model->find((int)$_GET['id']); $this->view('jam/form', ['title'=>'Edit Jam Pemakaian','item'=>$item,'standardSlots'=>$model->slotOptions($item)]); }
    public function update(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new JamPemakaian())->update((int)$_POST['id'], $_POST), 'Data jam berhasil diubah.', 'jam'); }
    public function delete(): void { AuthMiddleware::requireRole(['admin']); $this->runAction(fn() => (new JamPemakaian())->delete((int)$_GET['id']), 'Data jam berhasil dihapus.', 'jam'); }
}
