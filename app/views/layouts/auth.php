<?php
$flashSuccess = flash('success');
$flashError = flash('error');
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Masuk') ?> - <?= e(config_app('name')) ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <!-- Jadwal design system -->
    <link rel="stylesheet" href="<?= url('assets/css/app.css?v=20261004-login-v12') ?>">
</head>
<body class="auth-page">
    
    <main class="auth-shell">
        <!-- Flash Message Alerts -->
        <?php if ($flashSuccess): ?>
            <div role="status" class="auth-alert auth-alert-success">
                <i class="fa-solid fa-circle-check" style="font-size:1.1rem;"></i>
                <span><?= e($flashSuccess) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div role="alert" class="auth-alert auth-alert-error">
                <i class="fa-solid fa-circle-exclamation" style="font-size:1.1rem;"></i>
                <span><?= e($flashError) ?></span>
            </div>
        <?php endif; ?>

        <?php require $viewFile; ?>
    </main>

    <!-- Scripts -->
    <script>
        window.AppFlash = <?= json_encode(['success' => $flashSuccess, 'error' => $flashError], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="<?= url('assets/js/app.js?v=20261004-login-v11') ?>"></script>
</body>
</html>
