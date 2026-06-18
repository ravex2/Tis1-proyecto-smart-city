<?php

    require_once __DIR__ . '/../../controllers/area.controlador.php';
    

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $controller = new AreaController();

        $resultado = $controller->obtenerArea();
        
        if($resultado) {
            header('Location: ?ruta=departamentos');
        } else {
            echo "Error al obtener las áreas municipales.";
        }
    }
?>