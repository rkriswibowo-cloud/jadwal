<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/jadwal/login';
$_SERVER['SCRIPT_NAME'] = '/jadwal/index.php';
$_GET['url'] = 'login';
ob_start();
require 'public/index.php';
$output = ob_get_clean();
echo "Output length: " . strlen($output) . "\n";
if (strpos($output, 'Aplikasi BIMA') !== false) {
    echo "BIMA header rendered successfully!\n";
}
if (strpos($output, 'Fatal error') !== false || strpos($output, 'Notice:') !== false || strpos($output, 'Warning:') !== false) {
    echo "Warning or Error detected in output!\n";
    echo substr($output, 0, 500) . "\n";
} else {
    echo "Clean output without any PHP errors/warnings!\n";
}
