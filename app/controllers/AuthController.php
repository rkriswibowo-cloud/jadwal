<?php
class AuthController extends Controller
{
    public function login(): void
    {
        if (user()) redirect('dashboard');
        $this->viewAuth('auth/login', ['title' => 'Login']);
    }

    public function authenticate(): void
    {
        $identifier = trim((string) ($_POST['username'] ?? $_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $failedRedirect = $this->loginFallback($_POST['redirect_to'] ?? 'login');
        
        $userModel = new User();
        $user = $userModel->findByIdentifier($identifier) ?: $userModel->findByEmail($identifier);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'prodi_id' => $user['prodi_id'],
            ];
            redirect('dashboard');
        }
        flash('error', 'Username atau password salah.');
        redirect($failedRedirect);
    }

    public function logout(): void
    {
        session_destroy();
        redirect('');
    }

    private function loginFallback(string $path): string
    {
        $path = trim($path, '/');
        return in_array($path, ['', 'home', 'login'], true) ? $path : 'login';
    }
}
