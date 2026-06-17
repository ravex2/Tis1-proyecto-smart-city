<?php

    require_once __DIR__ . '/../../controllers/areaController.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $controller = new areaController();

        $resultado = $controller->crearArea($_POST['nombre'],$_POST['descripcion'], $_POST['id_municipalidad']);

        if($resultado) {
            header('Location: ../../views/areas_municipales/index.php');
        } else {
            echo "Error al crear el área municipal.";
        }
    }
?>