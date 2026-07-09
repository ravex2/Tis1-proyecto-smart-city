<?php

require_once __DIR__ . '/../config/mailconfig.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class EmailService {
    private $mailer;

    public function __construct() {
        // Obtener mailer configurado desde MailConfig
        $this->mailer = MailConfig::getMailer();
    }

    public function enviarVerificacion(string $email, string $nombre,string $rut, string $token): bool {
        $link = "http://localhost/Tis1-proyecto-smart-city/?ruta=verificar-email&token=" . $token . "&email=" . urlencode($email) . "&rut=" . urlencode($rut);

        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();
            
            $this->mailer->addAddress($email, $nombre);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Confirma tu cuenta - Portal Ciudadano';
            
            $this->mailer->Body = $this->obtenerPlantillaHTML($nombre, $link);
            
            $this->mailer->AltBody = "Hola $nombre,\n\nPor favor confirma tu cuenta visitando este enlace:\n$link\n\nEste enlace expira en 24 horas.\n\nSaludos,\nPortal Ciudadano";

            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Error enviando email a $email: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    public function enviarRecuperacion(string $email, string $nombre, string $link): bool {
        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();
            
            $this->mailer->addAddress($email, $nombre);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Recuperación de contraseña - Portal Ciudadano';
            
            $this->mailer->Body = $this->obtenerPlantillaHTMLRecuperacion($nombre, $link);
            
            $this->mailer->AltBody = "Hola $nombre,\n\nHas solicitado restablecer tu contraseña. Usa este enlace:\n$link\n\nEste enlace expira en 1 hora.\n\nSaludos,\nPortal Ciudadano";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Error enviando email de recuperación a $email: " . $this->mailer->ErrorInfo);
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
                                        © 2026 Portal Ciudadano - Municipalidad Digital
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

    private function obtenerPlantillaHTMLRecuperacion(string $nombre, string $link): string {
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
                                    <h2 style='color: #333333; margin-top: 0;'>Hola, {$nombre}</h2>
                                    <p style='color: #666666; font-size: 16px; line-height: 1.6;'>
                                        Recibimos una solicitud para restablecer la contraseña de tu cuenta.
                                    </p>
                                    <table cellpadding='0' cellspacing='0' style='margin: 30px 0;'>
                                        <tr>
                                            <td style='background-color: #ff6b6b; border-radius: 4px;'>
                                                <a href='{$link}' 
                                                   style='display: inline-block; padding: 14px 30px; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold;'>
                                                   Restablecer mi contraseña
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style='color: #999999; font-size: 14px;'>
                                        Si el botón no funciona, copia este enlace:<br>
                                        <span style='word-break: break-all;'>{$link}</span>
                                    </p>
                                    <p style='color: #999999; font-size: 14px; margin-top: 30px;'>
                                        Este enlace expira en 1 hora. Si no solicitaste este cambio, ignora este mensaje.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style='background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-radius: 0 0 8px 8px;'>
                                    <p style='color: #999999; font-size: 12px; margin: 0;'>
                                        © 2026 Portal Ciudadano - Municipalidad Digital
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