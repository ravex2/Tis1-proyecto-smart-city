<?php
    require_once __DIR__ . '/../../controllers/negocio.controlador.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $controller = new NegocioController();

        $resultado = $controller->crearNegocio(
            $_POST['nombre_negocio'] ?? '',
            $_POST['id_rubro'] ?? '',
            $_POST['id_sector'] ?? '',
            $_POST['direccion'] ?? '',       
            $_POST['correo_electronico'] ?? '', 
            $_POST['facebook'] ?? '',
            $_POST['whatsapp'] ?? '',
            $_POST['instagram'] ?? '',
            $_POST['dias_abierto'] ?? '',  
            $_POST['hora_apertura'] ?? '',
            $_POST['hora_cierre'] ?? '',
            $_POST['descripcion'] ?? '',
            $_FILES['imagenes'] ?? []
        );

        if ($resultado) {
            header('Location: ?ruta=comercio');
            exit();
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>Error al crear el negocio y sus imágenes.</div>";
        }
    }
?>