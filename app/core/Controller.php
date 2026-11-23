<?php
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/main.php';
    }

    protected function viewAuth(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/auth.php';
    }

    protected function viewPublic(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/public.php';
    }

    protected function runAction(callable $action, string $successMessage, string $redirectPath): void
    {
        try {
            $action();
            flash('success', $successMessage);
        } catch (PDOException $e) {
            flash('error', $this->databaseMessage($e));
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
        }

        redirect($redirectPath);
    }

    private function databaseMessage(PDOException $e): string
    {
        if (($e->errorInfo[0] ?? '') === '23000') {
            return 'Data tidak dapat disimpan karena masih duplikat atau sedang dipakai data lain.';
        }

        return 'Terjadi kesalahan database. Silakan cek data lalu coba lagi.';
    }
}
