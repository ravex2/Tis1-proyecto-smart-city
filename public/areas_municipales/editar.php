<?php

    require_once __DIR__ . '/../../controllers/area.controlador.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $controller = new AreaController();

        $resultado = $controller->editarArea($_POST['id_area'],
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['id_municipalidad']);

        if($resultado) {
            header('Location: ?ruta=departamentos');
        } else {
            echo "Error al editar el área municipal.";
        }
    }
?>