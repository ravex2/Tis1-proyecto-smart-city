<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailConfig {
    public static function getMailer(): PHPMailer {
        $mailer = new PHPMailer(true);
        
        echo $_ENV['SMTP_HOST'];
        try {
            // Configuración SMTP
            $mailer->isSMTP();
            $mailer->Host       = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $mailer->SMTPAuth   = true;
            $mailer->Username   = $_ENV['SMTP_USER'] ?? '';
            $mailer->Password   = str_replace(' ', '', $_ENV['SMTP_PASS'] ?? '');
            
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port       = $_ENV['SMTP_PORT'] ?? 587;
            
            // Configuración adicional
            $mailer->CharSet    = 'UTF-8';
            $mailer->Timeout    = 30;
            $mailer->SMTPDebug  = 0; // Cambia a 2 para debug
            
            $mailer->setFrom(
                $_ENV['SMTP_USER'] ?? 'noreply@tudominio.com', 
                'Portal Ciudadano'
            );
            
            return $mailer;
            
        } catch (Exception $e) {
            error_log("Error inicializando mailer: " . $e->getMessage());
            throw $e;
        }
    }
}