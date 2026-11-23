<?php
$router->get('', [HomeController::class, 'index']);
$router->get('home', [HomeController::class, 'index']);

$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'authenticate']);
$router->get('logout', [AuthController::class, 'logout']);
$router->get('dashboard', [DashboardController::class, 'index']);

$router->get('ruang', [RuangController::class, 'index']);
$router->get('ruang/create', [RuangController::class, 'create']);
$router->post('ruang/store', [RuangController::class, 'store']);
$router->get('ruang/edit', [RuangController::class, 'edit']);
$router->post('ruang/update', [RuangController::class, 'update']);
$router->get('ruang/delete', [RuangController::class, 'delete']);

$router->get('prodi', [ProdiController::class, 'index']);
$router->get('prodi/create', [ProdiController::class, 'create']);
$router->post('prodi/store', [ProdiController::class, 'store']);
$router->get('prodi/edit', [ProdiController::class, 'edit']);
$router->post('prodi/update', [ProdiController::class, 'update']);
$router->get('prodi/delete', [ProdiController::class, 'delete']);

$router->get('kelas', [KelasController::class, 'index']);
$router->get('kelas/create', [KelasController::class, 'create']);
$router->post('kelas/store', [KelasController::class, 'store']);
$router->get('kelas/edit', [KelasController::class, 'edit']);
$router->post('kelas/update', [KelasController::class, 'update']);
$router->get('kelas/delete', [KelasController::class, 'delete']);

$router->get('mata-kuliah', [MataKuliahController::class, 'index']);
$router->get('mata-kuliah/create', [MataKuliahController::class, 'create']);
$router->post('mata-kuliah/store', [MataKuliahController::class, 'store']);
$router->get('mata-kuliah/edit', [MataKuliahController::class, 'edit']);
$router->post('mata-kuliah/update', [MataKuliahController::class, 'update']);
$router->get('mata-kuliah/delete', [MataKuliahController::class, 'delete']);

$router->get('dosen', [DosenController::class, 'index']);
$router->get('dosen/create', [DosenController::class, 'create']);
$router->post('dosen/store', [DosenController::class, 'store']);
$router->get('dosen/edit', [DosenController::class, 'edit']);
$router->post('dosen/update', [DosenController::class, 'update']);
$router->get('dosen/delete', [DosenController::class, 'delete']);

$router->get('jam', [JamController::class, 'index']);
$router->get('jam/create', [JamController::class, 'create']);
$router->post('jam/store', [JamController::class, 'store']);
$router->get('jam/edit', [JamController::class, 'edit']);
$router->post('jam/update', [JamController::class, 'update']);
$router->get('jam/delete', [JamController::class, 'delete']);

$router->get('jadwal', [JadwalController::class, 'index']);
$router->get('jadwal/cetak', [JadwalController::class, 'cetak']);
$router->get('jadwal/cetak-dosen', [JadwalController::class, 'cetakDosen']);
$router->get('jadwal/create', [JadwalController::class, 'create']);
$router->post('jadwal/store', [JadwalController::class, 'store']);
$router->get('jadwal/edit', [JadwalController::class, 'edit']);
$router->post('jadwal/update', [JadwalController::class, 'update']);
$router->get('jadwal/delete', [JadwalController::class, 'delete']);
