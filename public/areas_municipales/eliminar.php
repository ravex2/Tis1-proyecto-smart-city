<?php

    require_once __DIR__ . '/../../controllers/areaController.php';
    

    if (isset($_GET['id_enviado'])) {

        $controller = new areaController();

        $resultado = $controller->eliminarArea($_GET['id_enviado']);

        if ($resultado) {
            header('Location: ../../views/areas_municipales/index.php');
            exit;
        }else {
            echo "Error al eliminar el área municipal.";
        }
    }
?>