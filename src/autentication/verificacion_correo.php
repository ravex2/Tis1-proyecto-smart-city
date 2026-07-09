<?php
require_once __DIR__ . '/../../controllers/autenticacion.controlador.php';

$auth = new AuthController();

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if ($email && $token) {
    if ($auth->verificarTokenEmail($email, $token)) {
        $_SESSION['mensaje_exito'] = 'Email verificado correctamente. Ya puedes iniciar sesión.';
        header('Location: ?ruta=dashboard');
    } else {
        $_SESSION['login_error'] = 'Token inválido o expirado.';
        header('Location: ?ruta=login');
    }
} else {
    $_SESSION['login_error'] = 'Parámetros inválidos.';
    header('Location: ?ruta=login');
}
exit();