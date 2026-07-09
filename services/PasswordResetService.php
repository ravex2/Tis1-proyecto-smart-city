<?php

require_once __DIR__ . '/../models/password_reset.php';

class PasswordResetService {
    private PasswordReset $model;

    public function __construct() {
        $this->model = new PasswordReset();
    }

    public function generarToken(string $correo): string {
        $this->model->deleteByEmail($correo);

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $createdAt = date('Y-m-d H:i:s');

        $this->model->create([
            'correo' => $correo,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'created_at' => $createdAt,
        ]);

        return $token;
    }

    public function validarToken(string $token): ?array {
        return $this->model->findValidByToken($token);
    }

    public function limpiarToken(string $token): bool {
        return $this->model->deleteByToken($token);
    }
}
