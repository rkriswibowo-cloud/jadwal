<?php
$flashSuccess = flash('success');
$flashError = flash('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config_app('name')) ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <!-- OBE Design System for Jadwal -->
    <link rel="stylesheet" href="<?= url('assets/css/app.css?v=20261004-obe-v7') ?>">

    <!-- Anti-FOUC Script -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme);
                document.documentElement.classList.remove('dark', 'light');
                document.documentElement.classList.add(savedTheme);

                const savedPalette = localStorage.getItem('accent_palette') || 'slate';
                document.documentElement.setAttribute('data-palette', savedPalette);
            } catch(e) {}
        })();
    </script>
</head>
<body class="public-page">
    <?php require $viewFile; ?>

    <script>
        window.AppFlash = <?= json_encode(['success' => $flashSuccess, 'error' => $flashError], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="<?= url('assets/js/app.js?v=20261004-obe') ?>"></script>
</body>
</html>
