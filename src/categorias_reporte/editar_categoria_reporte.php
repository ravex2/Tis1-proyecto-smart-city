<?php
    require_once __DIR__ . "/../../config/database.php";

    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        $consulta = "SELECT * FROM categoria_reporte WHERE id_categoria=$id_capturado";
        $db = getDatabase();
        $resultado =$db->query($consulta);
        $fila = $resultado[0] ?? null;

        if(!$fila){
            header("Location: leer_categoria_reporte");
            exit();
        }


    }else{
        echo "No existe este ID";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nombre_categoria = $_POST["nombre_categoria"];
        if($nombre_categoria !=""){
            $update = "UPDATE categoria_reporte SET nombre_categoria = '$nombre_categoria' WHERE id_categoria = $id_capturado";
            $resultado = $db->query($update);
            
            if($resultado){
                header("Location: leer_categoria_reporte");
                exit();
            }else{
                echo "Error al actualizar";
            }
        }else{
            echo "El nombre no puede estar vacio";
        }
    }


?>

<h2>Editar Nombre Categoria Reporte</h2>

<form method="POST">
    <input type="text" name="nombre_categoria" 
           value="<?php echo $fila['nombre_categoria']; ?>" required>

    <button type="submit">Actualizar</button>
</form>