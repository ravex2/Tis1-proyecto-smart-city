<?php

require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../services/MailService.php';
require_once __DIR__ . '/../controllers/sesion.controlador.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AuthController {
    private $usuarioModel;
    private $emailService;
    private $sessionController;
    
    public function __construct($usuarioModel = null) {
        $this->usuarioModel = $usuarioModel ?? new Usuario();
        $this->sessionController = new SesionController();
        $this->emailService = new EmailService(); 
    }

    // login with cookie
    private function crearSesionPersistente(string $rut): string {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token); // Guardamos el hash en la BD por seguridad

        $this->sessionController->crearSesion([
            'token_sesion' => $tokenHash,
            'rut_usuario' => $rut,
            'tipo_sesion' => 'activa',
            'fecha_inicio' => date('Y-m-d H:i:s'),
            'fecha_termino' => date('Y-m-d H:i:s', strtotime('+30 days')),
        ]);

        $expires = time() + (30 * 24 * 60 * 60); 
        setcookie('session_token', $token, [
            'expires' => $expires,
            'path' => '/',
            'secure' => false, // TRUE en producción (HTTPS)
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        return $token;
    }

    public function login(string $email, string $password, bool $rememberMe = false) {
        $user = $this->usuarioModel->findByCorreo($email);

        if (!$user) {
            return false;
        }
        $stored = $user['contrasenha'] ?? '';
        $authenticated = false;

        if (password_verify($password, $stored) || $password === $stored) {
            $authenticated = true;
        }
        if ($authenticated) {
            $id_rol = intval($user['id_rol'] ?? 0);

            // 1. Buscamos sus permisos específicos
            $permisos = $this->usuarioModel->obtenerPermisosPorRol($id_rol) ?? [];
            $user['permisos'] = $permisos;

            if ($rememberMe) {
                $this->crearSesionPersistente($user['rut']);
            }
            
            return $user;
        }
        return false;
    }



    public function registro(string $email, string $password, string $confirm_password, array $datosUsuario = [],bool $rememberMe = false): array {
        // 1. Validaciones básicas
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email inválido'];
        }

        if ($password !== $confirm_password) {
            return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
        }

        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres'];
        }
        

        // 2. Verificar si ya existe
        if ($this->usuarioModel->findByCorreo($email)) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 4. Generar token de verificación
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        // 5. Preparar datos del usuario
        $datos = [
            'rut'                => $datosUsuario['rut'] ?? '',
            'nombre'             => $datosUsuario['nombre'] ?? '',
            'apellido'           => $datosUsuario['apellido'] ?? '',
            'correo'             => $email, // Usar el email validado
            'direccion'          => $datosUsuario['direccion'] ?? '',
            'contrasenha'        => $hashedPassword, 
            'id_rol'             => 1, // Asignar rol por defecto
            'id_sector'          => $datosUsuario['id_sector'] ?? 1,
        ];

        try {
            $this->usuarioModel->create($datos);
        } catch (Exception $e) {
            error_log("Error creando usuario: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al crear la cuenta'];
        }

        if ($rememberMe) {
            $this->crearSesionPersistente($datos['rut']);
        }

        // Recuperar el usuario recién creado para devolver sus datos completos
        $createdUser = $this->usuarioModel->findByCorreo($email);
        if ($createdUser) {
            $id_rol = intval($createdUser['id_rol'] ?? 0);
            $permisos = $this->usuarioModel->obtenerPermisosPorRol($id_rol) ?? [];
            $createdUser['permisos'] = $permisos;

            return [
                'success' => true,
                'message' => 'Registro exitoso. Revisa tu email para confirmar tu cuenta.',
                'user' => $createdUser
            ];
        }

        return [
            'success' => true,
            'message' => 'Registro exitoso. Revisa tu email para confirmar tu cuenta.'
        ];
    }
    public function verificarEmailUsuario(string $email, string $rut = ''): bool {
        $user = $this->usuarioModel->findByCorreo($email);
        if (!$user) {
            return false;
        }
        /*
        if ($rut !== '' && ($user['rut'] ?? '') !== $rut) {
            return false;
        }
        */

        return (int)($user['email_verificado'] ?? 0) === 1;
    }

    public function procesarVerificacionCorreo(string $email, string $token, string $rut = ''): array {
        if ($email === '' || $token === '') {
            return ['success' => false, 'message' => 'Parámetros inválidos.'];
        }

        $user = $this->usuarioModel->findByCorreo($email);
        if (!$user) {
            return ['success' => false, 'message' => 'No se encontró el usuario.'];
        }
        /*
        if ($rut !== '' && ($user['rut'] ?? '') !== $rut) {
            return ['success' => false, 'message' => 'El enlace no corresponde a este usuario.'];
        }
        */

        if ((int)($user['email_verificado'] ?? 0) === 1) {
            return [
                'success' => true,
                'message' => 'El correo ya estaba verificado.',
                'user' => $user
            ];
        }

        if ($this->usuarioModel->marcarEmailVerificado($user['rut'])) {

            // Actualizar el estado del usuario
            $user['email_verificado'] = 1;

            return [
                'success' => true,
                'message' => 'Email verificado correctamente.',
                'user' => $user
            ];
        }

        return ['success' => false, 'message' => 'No se pudo verificar el email.'];
    }

    public function enviarEmailVerificacion(string $email, string $nombre,string $rut, string $token): bool {
        return $this->emailService->enviarVerificacion($email, $nombre,$rut, $token);
    }
    
    # actualizar que se verifico el email en la base de datos
    public function actualizarVerificarEmail(string $email): bool {
        try {
            $user = $this->usuarioModel->findByCorreo($email);
            if (!$user) {
                return false;
            }

            return $this->usuarioModel->marcarEmailVerificado($user['rut']);
        } catch (Exception $e) {
            error_log("Error actualizando verificación: " . $e->getMessage());
            return false;
        }
    }
    


    public function eliminarCookiesSesion() {
        // 1. Eliminar la cookie
        if (isset($_COOKIE['session_token'])) {
            $token = $_COOKIE['session_token'];
            setcookie('session_token', '', time() - 3600, '/'); // Expira la cookie

            // 2. Eliminar de la base de datos
            $tokenHash = hash('sha256', $token);
            $sql = "DELETE FROM sesion WHERE token_sesion = ?";
            $stmt = $this->usuarioModel->pdo->prepare($sql);
            $stmt->execute([$tokenHash]);
        }
    }



}