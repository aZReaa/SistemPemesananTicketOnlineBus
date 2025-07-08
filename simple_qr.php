<?php
// Generator QR Code sederhana yang pasti bekerja
header('Content-Type: image/png');
header('Cache-Control: no-cache');

$data = $_GET['data'] ?? 'TIKET';
$size = isset($_GET['size']) ? max(100, min(400, intval($_GET['size']))) : 150;

// Cek apakah GD extension tersedia
if (!extension_loaded('gd')) {
    // Jika GD tidak tersedia, redirect ke API eksternal
    $encoded_data = urlencode($data);
    header("Location: https://api.qrserver.com/v1/create-qr-code/?data={$encoded_data}&size={$size}x{$size}");
    exit;
}

// Buat gambar sederhana dengan GD
$img = imagecreate($size, $size);

// Warna
$white = imagecolorallocate($img, 255, 255, 255);
$black = imagecolorallocate($img, 0, 0, 0);
$blue = imagecolorallocate($img, 0, 100, 200);

// Background putih
imagefill($img, 0, 0, $white);

// Border
imagerectangle($img, 0, 0, $size-1, $size-1, $black);
imagerectangle($img, 2, 2, $size-3, $size-3, $blue);

// Teks di tengah
$font_size = 3;
$text_width = imagefontwidth($font_size) * strlen($data);
$text_height = imagefontheight($font_size);
$x = ($size - $text_width) / 2;
$y = ($size - $text_height) / 2;

// Background teks
imagefilledrectangle($img, $x-5, $y-5, $x+$text_width+5, $y+$text_height+5, $white);
imagerectangle($img, $x-5, $y-5, $x+$text_width+5, $y+$text_height+5, $black);

// Tulis teks
imagestring($img, $font_size, $x, $y, $data, $black);

// Output gambar
imagepng($img);
imagedestroy($img);
?>
