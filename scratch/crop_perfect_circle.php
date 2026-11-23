<?php
$srcPath = 'C:/Users/ASUS/.gemini/antigravity-ide/brain/160167e4-7293-43da-b921-375fb50ab481/.user_uploaded/media_1791091756996.png';
$im = imagecreatefrompng($srcPath);

// Koordinat lingkaran avatar:
// X: 64 to 125, Y: 132 to 193
// Diameter = 62px, radius = 31px, center = (94.5, 162.5)
$cx = 94.5;
$cy = 162.5;
$radius = 31.5;

$targetSize = 180; // Scale up 3x agar tajam di layar retina / mobile
$target = imagecreatetruecolor($targetSize, $targetSize);
imagealphablending($target, false);
imagesavealpha($target, true);
$transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
imagefilledrectangle($target, 0, 0, $targetSize, $targetSize, $transparent);

// Sampling dengan antialiasing dan mask lingkaran
for ($ty = 0; $ty < $targetSize; $ty++) {
    for ($tx = 0; $tx < $targetSize; $tx++) {
        $dx = ($tx - $targetSize / 2) / ($targetSize / 2);
        $dy = ($ty - $targetSize / 2) / ($targetSize / 2);
        $dist = sqrt($dx * $dx + $dy * $dy);
        
        if ($dist <= 1.0) {
            // Map ke source
            $sx = $cx + $dx * $radius;
            $sy = $cy + $dy * $radius;
            
            $rgb = imagecolorat($im, (int)round($sx), (int)round($sy));
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            
            // Antialias tepi luar lingkaran
            $alpha = 0;
            if ($dist > 0.96) {
                $alpha = (int)round((($dist - 0.96) / 0.04) * 127);
                if ($alpha > 127) $alpha = 127;
            }
            
            $col = imagecolorallocatealpha($target, $r, $g, $b, $alpha);
            imagesetpixel($target, $tx, $ty, $col);
        }
    }
}

imagepng($target, 'C:/xampp/htdocs/jadwal/public/assets/images/bima_logo.png');
echo "Perfect circular high-res logo generated at public/assets/images/bima_logo.png!\n";
