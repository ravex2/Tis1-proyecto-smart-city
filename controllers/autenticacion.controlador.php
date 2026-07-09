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
        $authenticated = false;

        // Si la contraseña en la DB está hasheada con password_hash
        if (password_verify($password, $stored) || $password === $stored) {
            $authenticated = true;
        }
        if ($authenticated) {
            $id_rol = intval($user['id_rol'] ?? 0);

            // 1. Buscamos sus permisos específicos
            $permisos = $this->usuarioModel->obtenerPermisosPorRol($id_rol) ?? [];
            $user['permisos'] = $permisos;
                        
            return $user;
        }
        return false;
    }
}
