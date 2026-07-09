<?php

require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/password_reset.php';
require_once __DIR__ . '/../services/MailService.php';

class PasswordResetController {
    private Usuario $usuarioModel;
    private PasswordReset $passwordResetModel;
    private EmailService $emailService;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->passwordResetModel = new PasswordReset();
        $this->emailService = new EmailService();
    }

    public function solicitarRecuperacion(string $correo): array {
        $usuario = $this->usuarioModel->findByCorreo($correo);
        if (!$usuario) {
            return ['success' => false, 'message' => 'No existe una cuenta asociada a ese correo.'];
        }

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->passwordResetModel->deleteByEmail($correo);
        $this->passwordResetModel->create([
            'correo' => $correo,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $link = "http://localhost/Tis1-proyecto-smart-city/?ruta=restablecer-contrasena&token=" . urlencode($token);
        $this->emailService->enviarRecuperacion($correo, $usuario['nombre'], $link);

        return ['success' => true, 'message' => 'Hemos enviado un email con instrucciones para restablecer tu contraseña.'];
    }

    public function validarToken(string $token): ?array {
        return $this->passwordResetModel->findValidByToken($token);
    }

    public function restablecerContrasena(string $token, string $nuevaContrasena, string $confirmarContrasena): array {
        if ($nuevaContrasena !== $confirmarContrasena) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden.'];
        }

        if (strlen($nuevaContrasena) < 6) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres.'];
        }

        $registro = $this->passwordResetModel->findValidByToken($token);
        if (!$registro) {
            return ['success' => false, 'message' => 'El enlace ya no es válido o ha expirado.'];
        }

        $usuario = $this->usuarioModel->findByCorreo($registro['correo']);
        if (!$usuario) {
            return ['success' => false, 'message' => 'Usuario no encontrado.'];
        }

        $hashedPassword = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $this->usuarioModel->update($usuario['rut'], ['contrasenha' => $hashedPassword]);
        $this->passwordResetModel->deleteByToken($token);

        return ['success' => true, 'message' => 'Contraseña actualizada con éxito.'];
    }
}
