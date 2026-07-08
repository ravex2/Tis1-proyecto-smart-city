<?php
    require_once __DIR__ . "/../../config/database.php";

    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $consulta = "DELETE FROM categoria_reporte WHERE id_categoria= ?";
        $db = getDatabase();
        $resultado =$db->execute($consulta,[$id_capturado]);
        if ($resultado) {
            header("Location: leer_categoria_reporte");
            exit();
        } else {
            echo "Error al eliminar";
        }
    }else{
        echo "No existe este ID";
    }

    
    
    

?>