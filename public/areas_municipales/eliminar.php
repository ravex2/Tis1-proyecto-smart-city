<?php

    require __DIR__ . '/../controllers/areaController.php';
    
    $controller = new areaController();

    $controller->eliminarArea($_POST['id_area']);

?>