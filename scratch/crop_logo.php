<?php
$srcPath = 'C:/Users/ASUS/.gemini/antigravity-ide/brain/160167e4-7293-43da-b921-375fb50ab481/.user_uploaded/media_1791091756996.png';
$im = imagecreatefrompng($srcPath);

// Mari cari batas lingkaran logo avatar di sekitar x: 50..150, y: 120..220
// Lingkaran luar memiliki border biru gelap/navy di luar putih
$minX = 999; $maxX = 0; $minY = 999; $maxY = 0;

for ($y = 120; $y < 220; $y++) {
    for ($x = 50; $x < 150; $x++) {
        $rgb = imagecolorat($im, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Cek apakah bukan warna background header biru (#e4edf9 ~ r:228, g:237, b:249) dan bukan warna card putih (#fff)
        // Background card putih: r>250, g>250, b>250
        // Background header: r: 215-240, g: 225-245, b: 240-255
        $isBg = ($r > 210 && $g > 220 && $b > 235) || ($r > 250 && $g > 250 && $b > 250);
        if (!$isBg) {
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}

echo "Detected Logo Bounds: X: $minX to $maxX (width: " . ($maxX - $minX + 1) . "), Y: $minY to $maxY (height: " . ($maxY - $minY + 1) . ")\n";

$pad = 2;
$cropX = $minX - $pad;
$cropY = $minY - $pad;
$cropW = ($maxX - $minX + 1) + ($pad * 2);
$cropH = ($maxY - $minY + 1) + ($pad * 2);

// Buat canvas persegi untuk logo
$size = max($cropW, $cropH);
$cropped = imagecreatetruecolor($size, $size);
imagealphablending($cropped, false);
imagesavealpha($cropped, true);
$transparent = imagecolorallocatealpha($cropped, 0, 0, 0, 127);
imagefilledrectangle($cropped, 0, 0, $size, $size, $transparent);

imagecopyresampled($cropped, $im, (int)(($size - $cropW)/2), (int)(($size - $cropH)/2), $cropX, $cropY, $cropW, $cropH, $cropW, $cropH);

imagepng($cropped, 'C:/xampp/htdocs/jadwal/public/assets/images/bima_logo.png');
echo "Saved to public/assets/images/bima_logo.png successfully!\n";
