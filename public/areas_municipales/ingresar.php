<?php

    require __DIR__ . '/../controllers/areaController.php';
    
    $controller = new areaController();

    $controller->crearArea($_POST['nombre'],$_POST['descripcion'], $_POST['id_municipalidad']);

?>