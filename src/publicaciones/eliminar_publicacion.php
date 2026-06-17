<?php
    require_once __DIR__ . "/../../config/database.php";

    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $consulta = "DELETE FROM publicacion WHERE id_publicacion=$id_capturado";
        $resultado =$conexion->query($consulta);
        if ($resultado) {
            header("Location: leer_publicacion");
            exit();
        } else {
            echo "Error al eliminar";
        }
    }else{
        echo "No existe este ID";
    }

    
    
    

?>