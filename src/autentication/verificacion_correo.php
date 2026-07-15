<?php
require_once __DIR__ . '/../../controllers/autenticacion.controlador.php';

session_start();

$auth = new AuthController();

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';
$rut = $_GET['rut'] ?? '';

if ($email && $token) {
    $resultado = $auth->procesarVerificacionCorreo($email, $token, $rut);
    echo $resultado["message"];
    if ($resultado['success']) {

        $_SESSION['mensaje_exito'] = $resultado['message'];

        if (isset($resultado['user'])) {
            $_SESSION['user'] = $resultado['user'];

            if ($resultado['user']['tipo_interfaz'] === 'interno') {
                header('Location: ?ruta=dashboard');
            } else {
                header('Location: ?ruta=publicaciones');
            }
            exit();
        }
    }

    $_SESSION['login_error'] = $resultado['message'];
} else {
    $_SESSION['login_error'] = 'Parámetros inválidos.';
}

header('Location: ?ruta=login');
exit();