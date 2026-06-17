<?php

    require_once __DIR__ . '/../../controllers/areaController.php';
    

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $controller = new areaController();

        $resultado = $controller->obtenerArea();
        
        if($resultado) {
            header('Location: ../../views/areas_municipales/index.php');
        } else {
            echo "Error al obtener las áreas municipales.";
        }
    }
?>