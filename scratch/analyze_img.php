<?php
$im = imagecreatefrompng('C:/Users/ASUS/.gemini/antigravity-ide/brain/160167e4-7293-43da-b921-375fb50ab481/.user_uploaded/media_1791091756996.png');
for ($y = 130; $y < 200; $y++) {
    $rgb = imagecolorat($im, 250, $y);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    if ($r > 250 && $g > 250 && $b > 250) {
        echo "Card body white begins at y = $y\n";
        break;
    }
}

// Bounding box of the card
for ($x = 20; $x < 100; $x++) {
    $rgb = imagecolorat($im, $x, 100);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    // Card header biru
    if ($r > 215 && $r < 235 && $g > 225 && $g < 245 && $b > 240) {
        echo "Card left edge at x = $x\n";
        break;
    }
}
for ($x = 500; $x > 400; $x--) {
    $rgb = imagecolorat($im, $x, 100);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    if ($r > 215 && $r < 235 && $g > 225 && $g < 245 && $b > 240) {
        echo "Card right edge at x = $x\n";
        break;
    }
}
