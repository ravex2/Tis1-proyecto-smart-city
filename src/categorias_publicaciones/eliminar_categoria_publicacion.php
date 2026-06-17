<?php
    require_once __DIR__ . "/../../config/database.php";

    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $consulta = "DELETE FROM categoria_publicacion WHERE id_categoria=$id_capturado";
        $resultado =$conexion->query($consulta);
        if ($resultado) {
            header("Location: leer_categoria_publicacion");
            exit();
        } else {
            echo "Error al eliminar";
        }
    }else{
        echo "No existe este ID";
    }

    
    
    

?>