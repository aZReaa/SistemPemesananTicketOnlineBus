<?php
/**
 * Simple QR Code Generator
 * Using Google Charts API for QR generation
 */

class QRCodeGenerator {
    
    /**
     * Generate QR code URL using simple local generator
     * @param string $data Data to encode in QR code
     * @param int $size Size of QR code (default: 200x200)
     * @return string QR code image URL
     */
    public static function generateQRCodeURL($data, $size = 200) {
        $encodedData = urlencode($data);
        
        // Gunakan generator sederhana yang pasti bekerja
        return "simple_qr.php?data={$encodedData}&size={$size}";
    }
    
    /**
     * Generate QR code for booking code
     * @param string $bookingCode Booking code
     * @param array $bookingData Additional booking data
     * @return string QR code URL
     */
    public static function generateBookingQR($bookingCode, $bookingData = []) {
        // Create QR data structure
        $qrData = [
            'type' => 'ticket',
            'booking_code' => $bookingCode,
            'timestamp' => time()
        ];
        
        // Add additional data if provided
        if (!empty($bookingData)) {
            $qrData = array_merge($qrData, $bookingData);
        }
        
        // Convert to JSON for QR code
        $jsonData = json_encode($qrData);
        
        return self::generateQRCodeURL($jsonData, 200);
    }
    
    /**
     * Decode QR code data
     * @param string $qrData QR code data (JSON string)
     * @return array|false Decoded data or false on failure
     */
    public static function decodeQRData($qrData) {
        $decoded = json_decode($qrData, true);
        
        // Validate QR data structure
        if (!$decoded || !isset($decoded['type']) || $decoded['type'] !== 'ticket') {
            return false;
        }
        
        return $decoded;
    }
    
    /**
     * Validate QR code data
     * @param array $qrData Decoded QR data
     * @return bool True if valid, false otherwise
     */
    public static function validateQRData($qrData) {
        if (!is_array($qrData)) {
            return false;
        }
        
        // Check required fields
        if (!isset($qrData['type']) || $qrData['type'] !== 'ticket') {
            return false;
        }
        
        if (!isset($qrData['booking_code']) || empty($qrData['booking_code'])) {
            return false;
        }
        
        // If it's simple format (from old QR codes), always valid
        if (isset($qrData['format']) && $qrData['format'] === 'simple') {
            return true;
        }
        
        // For JSON format, check timestamp
        if (isset($qrData['timestamp'])) {
            // Check if QR code is not too old (24 hours)
            $maxAge = 24 * 60 * 60; // 24 hours in seconds
            if (time() - $qrData['timestamp'] > $maxAge) {
                return false;
            }
        }
        
        return true;
    }
}
