<?php

    require_once __DIR__ . '/../../controllers/usuario.controlador.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new UsuarioController();

        $resultado = $controller->cambiarRol($_POST['rut'],
            $_POST['id_rol'],$_POST['id_area']);

        if ($resultado['ok']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }

        header('Location: ?ruta=roles_usuarios');
        exit;
    }
?>