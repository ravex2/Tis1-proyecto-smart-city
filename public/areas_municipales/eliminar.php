<?php

    require_once __DIR__ . '/../../controllers/area.controlador.php';
    

    if (isset($_GET['id_area'])) {

        $controller = new AreaController();

        $resultado = $controller->eliminarAreaCompleta($_GET['id_area']);

        if ($resultado) {
            header('Location: ?ruta=departamentos');
            exit;
        }else {
            echo "Error al eliminar el área municipal.";
        }
    }
?>