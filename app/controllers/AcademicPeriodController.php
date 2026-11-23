<?php
class AcademicPeriodController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::requireRole(['admin']);
        $this->view('periode/index', [
            'title' => 'Periode Akademik',
            'items' => (new AcademicPeriod())->catalog(),
        ]);
    }

    public function sync(): void
    {
        AuthMiddleware::requireRole(['admin']);
        require_account_csrf();
        $this->runAction(
            fn() => (new AcademicPeriod())->syncFromObe(),
            'Periode akademik berhasil disinkronkan dari OBE.',
            'periode-akademik'
        );
    }
}
