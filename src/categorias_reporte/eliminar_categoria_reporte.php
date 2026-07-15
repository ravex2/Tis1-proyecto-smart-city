<?php
    require_once __DIR__ . "/../../config/database.php";
    $db = getDatabase();
    if (isset($_GET["error"])) {

        if ($_GET["error"] == "existen_reportes") {
            echo '<div class="alert alert-danger">La categoria tiene reportes asociados.</div>';
        }
    }


    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $existe = $db->query( "SELECT COUNT(*) AS total FROM reporte WHERE id_categoria_reporte = ?",[$id_capturado]);

        if ($existe[0]["total"] > 0) {
            header("Location: ?ruta=leer_categoria_reporte&error=existen_reportes");
            exit();
        }else{
            
            $consulta = "DELETE FROM categoria_reporte WHERE id_categoria= ?";
            
            $resultado =$db->execute($consulta,[$id_capturado]);
            if ($resultado) {
                header("Location: ?ruta=reportes");
                exit();
            } else {
                echo "Error al eliminar";
            }
        }

        
    }else{
        echo "No existe este ID";
    }

    
    
    

?>