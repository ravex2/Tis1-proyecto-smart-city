<?php
require_once __DIR__ . '/../../controllers/usuario.controlador.php';


echo "Procesando la respuesta de Google...";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$auth = new UsuarioController();
$user = $auth->handleGoogleCallback();

if ($user) {
    $_SESSION['user'] = $user;

    if ($user['tipo_interfaz'] === 'interno') {
        header('Location: ?ruta=dashboard');
    } elseif ($user['tipo_interfaz'] === 'externo') {
        header('Location: ?ruta=publicaciones');
    } else {
        header('Location: ?ruta=login&error=tipo_interfaz_invalido');
    }

    exit();
} else {
    header('Location: ?ruta=login&error=google_auth_failed');
    exit();
}