<?php

require_once __DIR__ . '/../models/usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct($usuarioModel = null) {
        if ($usuarioModel) {
            $this->usuarioModel = $usuarioModel;
        } else {
            $this->usuarioModel = new Usuario();
        }
    }


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

        if ($password === $stored) {
            return $user;
        }

        return false;
    }
}
