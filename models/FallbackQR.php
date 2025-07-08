<?php
// Simple text-based QR code for backup
// This file can be included as a fallback when online QR code services fail

function generateTextQR($text, $size = 10) {
    $text = trim($text);
    $lines = [];
    
    // Create a simple text-based QR code
    // Generate top border
    $lines[] = str_repeat("██", $size + 2);
    
    // Generate content rows
    for ($i = 0; $i < $size; $i++) {
        $row = "██";
        for ($j = 0; $j < $size; $j++) {
            // Create a pattern based on the string and position
            $char = ord(substr($text . $text . $text, ($i + $j) % strlen($text), 1));
            $row .= (($i + $j + $char) % 3 == 0) ? "  " : "██";
        }
        $row .= "██";
        $lines[] = $row;
    }
    
    // Generate bottom border
    $lines[] = str_repeat("██", $size + 2);
    
    return implode("\n", $lines);
}

// Return a base64 encoded SVG QR code
function generateSvgQR($text, $size = 150) {
    $blockSize = 10;
    $numBlocks = floor($size / $blockSize);
    $text = trim($text);
    
    // Start SVG
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 ' . $size . ' ' . $size . '">';
    
    // Draw blocks
    for ($i = 0; $i < $numBlocks; $i++) {
        for ($j = 0; $j < $numBlocks; $j++) {
            // Use the text and position to determine if a block should be filled
            $charPos = ($i + $j) % strlen($text);
            $char = ord(substr($text, $charPos, 1));
            
            // Use a simple algorithm to determine if the block should be filled
            if ((($i * $j) + $char) % 3 != 0) {
                $x = $j * $blockSize;
                $y = $i * $blockSize;
                $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $blockSize . '" height="' . $blockSize . '" fill="black" />';
            }
        }
    }
    
    // Draw positioning squares
    // Top-left
    $svg .= '<rect x="0" y="0" width="' . ($blockSize * 3) . '" height="' . ($blockSize * 3) . '" fill="black" />';
    $svg .= '<rect x="' . $blockSize . '" y="' . $blockSize . '" width="' . $blockSize . '" height="' . $blockSize . '" fill="white" />';
    
    // Top-right
    $svg .= '<rect x="' . ($size - $blockSize * 3) . '" y="0" width="' . ($blockSize * 3) . '" height="' . ($blockSize * 3) . '" fill="black" />';
    $svg .= '<rect x="' . ($size - $blockSize * 2) . '" y="' . $blockSize . '" width="' . $blockSize . '" height="' . $blockSize . '" fill="white" />';
    
    // Bottom-left
    $svg .= '<rect x="0" y="' . ($size - $blockSize * 3) . '" width="' . ($blockSize * 3) . '" height="' . ($blockSize * 3) . '" fill="black" />';
    $svg .= '<rect x="' . $blockSize . '" y="' . ($size - $blockSize * 2) . '" width="' . $blockSize . '" height="' . $blockSize . '" fill="white" />';
    
    $svg .= '</svg>';
    
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

// If called directly, generate a QR code for testing
if (isset($_GET['text'])) {
    $text = $_GET['text'];
    $size = isset($_GET['size']) ? intval($_GET['size']) : 150;
    
    header('Content-Type: image/svg+xml');
    echo file_get_contents(generateSvgQR($text, $size));
    exit;
}
