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
        $nuevo_nombre = $_POST["nombre"];
        if($nuevo_nombre !=""){
            $update = "UPDATE categoria_reporte SET nombre = '$nuevo_nombre' WHERE id_categoria = $id_capturado";
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
    <input type="text" name="nombre" 
           value="<?php echo $fila['nombre']; ?>" required>

    <button type="submit">Actualizar</button>
</form>