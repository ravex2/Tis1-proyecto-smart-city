<?php
    require_once __DIR__ . "/../../config/database.php";

    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $db = getDatabase();

        try{
            $db->execute("DELETE FROM reaccion WHERE id_publicacion = ?",[$id_capturado]);
            $db->execute("DELETE FROM comentario WHERE id_publicacion = ?",[$id_capturado]);
            $db->execute("DELETE FROM publicacion WHERE id_publicacion = ?",[$id_capturado]);
            header("Location: ?ruta=leer_publicacion");
            exit();
        }catch (Exception $e) {
        echo "Error al eliminar: " . $e->getMessage();
        }
    }else{
        echo "No existe este ID";
    }

    
    
    

?>