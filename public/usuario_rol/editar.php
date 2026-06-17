<?php

    require_once __DIR__ . '/../../controllers/usuario.controlador.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new UsuarioController();

        $resultado = $controller->cambiarRol($_POST['rut'],
            $_POST['id_rol']);

        if($resultado) {
            header('Location: ../../views/pages/admin/asignacion_roles.php');
        } else {
            echo "Error al editar el área municipal.";
        }
    }
?>