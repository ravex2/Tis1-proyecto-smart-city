<?php

require_once __DIR__ . '/../models/user.php';

class AuthController
{
    private $usuarioModel;

    public function __construct($usuarioModel = null) {
        if ($usuarioModel) {
            $this->usuarioModel = $usuarioModel;
        } else {
            $this->usuarioModel = new Usuario();
        }
    }

    /**
     * Intenta autenticar un usuario por correo y contraseña.
     * Devuelve el registro de usuario en caso de éxito, o false si falla.
     */
    public function login(string $email, string $password)
    {
        $user = $this->usuarioModel->findByCorreo($email);

        if (!$user) {
            return false;
        }

        $stored = $user['contrasenha'] ?? '';

        // Si la contraseña en la DB está hasheada con password_hash
        if (password_verify($password, $stored)) {
            return $user;
        }

        // Soporte para contraseña en texto plano (como el caso "admin")
        if ($password === $stored) {
            return $user;
        }

        return false;
    }
}
