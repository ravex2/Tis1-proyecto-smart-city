<?php

    require_once __DIR__ . '/../../controllers/area.controlador.php';
    

    if (isset($_GET['id_enviado'])) {

        $controller = new AreaController();

        $resultado = $controller->eliminarArea($_GET['id_enviado']);

        if ($resultado) {
            header('Location: ?ruta=departamentos');
            exit;
        }else {
            echo "Error al eliminar el área municipal.";
        }
    }
?>