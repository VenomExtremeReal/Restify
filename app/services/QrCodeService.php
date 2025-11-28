<?php
/**
 * Serviço para geração de QR Codes
 */

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService {
    
    /**
     * Gerar QR Code em base64 para exibição direta
     */
    public static function generateBase64($data, $size = 300) {
        try {
            $options = new QROptions([
                'version'      => 5,
                'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'     => QRCode::ECC_L,
                'scale'        => 10,
                'imageBase64'  => true,
            ]);
            
            $qrcode = new QRCode($options);
            return $qrcode->render($data);
            
        } catch (Exception $e) {
            // Fallback: retornar URL de API pública
            return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . urlencode($data);
        }
    }
    
    /**
     * Gerar QR Code e salvar em arquivo
     */
    public static function generateFile($data, $filepath) {
        try {
            $options = new QROptions([
                'version'      => 5,
                'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'     => QRCode::ECC_L,
                'scale'        => 10,
            ]);
            
            $qrcode = new QRCode($options);
            $qrcode->render($data, $filepath);
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
