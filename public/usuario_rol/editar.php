<?php

    require_once __DIR__ . '/../../controllers/usuario.controlador.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new UsuarioController();
        session_start();

        $resultado = $controller->cambiarRol($_POST['rut'],
            $_POST['id_rol']);

        if ($resultado['ok']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }

        header('Location: ?ruta=roles_usuarios');
        exit;
    }
?>