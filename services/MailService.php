<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Configuración SMTP moderna
            $this->mailer->isSMTP();
            $this->mailer->Host       = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $_ENV['SMTP_USER'] ?? '';
            $this->mailer->Password   = $_ENV['SMTP_PASS'] ?? '';
            
            // ✅ CORRECCIÓN: Usar string directo en lugar de constante deprecada
            $this->mailer->SMTPSecure = 'starttls';  // En vez de PHPMailer::ENCRYPTION_STARTTLS
            $this->mailer->Port       = 587;
            
            // Configuración adicional recomendada
            $this->mailer->CharSet    = 'UTF-8';
            $this->mailer->Timeout    = 30;
            $this->mailer->SMTPDebug  = 0; // Cambia a 2 para debug
            
            $this->mailer->setFrom(
                $_ENV['SMTP_USER'] ?? 'noreply@tudominio.com', 
                'Portal Ciudadano'
            );
        } catch (Exception $e) {
            error_log("Error inicializando mailer: " . $e->getMessage());
        }
    }

    public function enviarVerificacion(string $email, string $nombre, string $token): bool {
        $link = "http://localhost/?ruta=verificar-email&token=" . $token . "&email=" . urlencode($email);

        try {
            // ✅ CORRECCIÓN: Limpiar destinatarios correctamente
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();
            
            $this->mailer->addAddress($email, $nombre);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Confirma tu cuenta - Portal Ciudadano';
            
            $this->mailer->Body = $this->obtenerPlantillaHTML($nombre, $link);
            
            // Versión texto plano (para clientes que no soportan HTML)
            $this->mailer->AltBody = "Hola $nombre,\n\nPor favor confirma tu cuenta visitando este enlace:\n$link\n\nEste enlace expira en 24 horas.\n\nSaludos,\nPortal Ciudadano";

            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error enviando email a $email: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    private function obtenerPlantillaHTML(string $nombre, string $link): string {
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
            <table width='100%' cellpadding='0' cellspacing='0' style='background-color: #f4f4f4; padding: 40px 0;'>
                <tr>
                    <td align='center'>
                        <table width='600' cellpadding='0' cellspacing='0' style='background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);'>
                            <tr>
                                <td style='background-color: #3d71ff; padding: 30px; text-align: center; border-radius: 8px 8px 0 0;'>
                                    <h1 style='color: #ffffff; margin: 0; font-size: 28px;'>Portal Ciudadano</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 40px 30px;'>
                                    <h2 style='color: #333333; margin-top: 0;'>¡Bienvenido, {$nombre}!</h2>
                                    <p style='color: #666666; font-size: 16px; line-height: 1.6;'>
                                        Gracias por registrarte. Por favor confirma tu email:
                                    </p>
                                    <table cellpadding='0' cellspacing='0' style='margin: 30px 0;'>
                                        <tr>
                                            <td style='background-color: #4CAF50; border-radius: 4px;'>
                                                <a href='{$link}' 
                                                   style='display: inline-block; padding: 14px 30px; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold;'>
                                                   Confirmar mi cuenta
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style='color: #999999; font-size: 14px;'>
                                        Si el botón no funciona, copia este enlace:<br>
                                        <span style='word-break: break-all;'>{$link}</span>
                                    </p>
                                    <p style='color: #999999; font-size: 14px; margin-top: 30px;'>
                                        <strong>Importante:</strong> Este enlace expira en 24 horas.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style='background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-radius: 0 0 8px 8px;'>
                                    <p style='color: #999999; font-size: 12px; margin: 0;'>
                                        © 2024 Portal Ciudadano - Municipalidad Digital
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";
    }
}