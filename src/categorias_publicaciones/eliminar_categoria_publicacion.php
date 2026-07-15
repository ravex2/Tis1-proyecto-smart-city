<?php
    require_once __DIR__ . "/../../config/database.php";
    $db = getDatabase();
    if (isset($_GET["error"])) {

        if ($_GET["error"] == "existen_publicaciones") {
            echo '<div class="alert alert-danger">La categoria tiene publicaciones asociadas.</div>';
        }
    }

    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $existe = $db->query( "SELECT COUNT(*) AS total FROM publicacion WHERE id_categoria = ?",[$id_capturado]);

        if ($existe[0]["total"] > 0) {
            header("Location: ?ruta=leer_categoria_publicacion&error=existen_publicaciones");
            exit();
        }else{

            $consulta = "DELETE FROM categoria_publicacion WHERE id_categoria=?";
            
            $resultado =$db->execute($consulta,[$id_capturado]);
            if ($resultado) {
                header("Location: ?ruta=leer_categoria_publicacion");
                exit();
            } else {
                echo "Error al eliminar";
            }
        }
    }else{
        echo "No existe este ID";
    }

    
    
    

?>