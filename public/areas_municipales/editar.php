<?php

    require __DIR__ . '/../controllers/areaController.php';
    
    $controller = new areaController();

    $controller->editarArea($_POST['id_area'],
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['id_municipalidad']);
    
?>