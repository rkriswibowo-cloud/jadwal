<?php
class ProfilController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireLogin();
        $this->view('profil/index', [
            'title' => 'Profil Saya',
            'account' => (new User())->findAccount((int)user()['id']),
        ]);
    }

    public function update(): void
    {
        AuthMiddleware::requireLogin();
        require_account_csrf();
        $this->runAction(
            fn() => (new User())->saveOwnProfile((int)user()['id'], $_POST),
            'Profil berhasil diperbarui.',
            'profil'
        );
    }
}
