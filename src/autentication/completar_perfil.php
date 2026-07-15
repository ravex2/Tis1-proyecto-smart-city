<?php
require_once __DIR__ . '/../../controllers/usuario.controlador.php';

$auth = new UsuarioController();
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'rut' => $_POST['rut'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'id_sector' => $_POST['id_sector'] ?? 1,
    ];
    
    $success = $auth->completeProfile($data);
    
    if ($success) {
        $user = $auth->filtrarCorreo($_SESSION['google_temp_user']['correo'] ?? '');
        
        if ($user) {
            $_SESSION['user'] = $user;
            unset($_SESSION['google_temp_user']);
            header('Location: ?ruta=dashboard');
            exit();
        } else {
            header('Location: ?ruta=completar_perfil&error=usuario_no_encontrado');
        }
    } else {
        $errorMessage = $_SESSION['error'] ?? 'Error al completar el perfil';
    }
} else {
    if (!isset($_SESSION['google_temp_user'])) {
        header('Location: ?ruta=login');
        exit();
    }
}

// Incluir la vista
include __DIR__ . '/../../views/pages/completar_perfil.php';