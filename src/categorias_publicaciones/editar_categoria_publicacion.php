<?php
    require_once __DIR__ . "/../../config/database.php";
    $db = getDatabase();
    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        
        
        $resultado =$db->query(
            "SELECT * FROM categoria_publicacion WHERE id_categoria = ?",
            [$id_capturado]
        );
        $fila = $resultado[0] ?? null;

        if(!$fila){
            header("Location: leer_categoria_publicacion");
            exit();
        }


    }else{
        echo "No existe este ID";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nuevo_nombre = $_POST["nombre"];
        if($nuevo_nombre !=""){
            $resultado = $db->execute(
                "UPDATE categoria_publicacion SET nombre = ? WHERE id_categoria = ?",
                [$nuevo_nombre,$id_capturado]
            );
            
            if($resultado){
                header("Location: leer_categoria_publicacion");
                exit();
            }else{
                echo "Error al actualizar";
            }
        }else{
            echo "El nombre no puede estar vacio";
        }
    }


?>

<h2>Editar Nombre Categoria Publicacion</h2>

<form method="POST">
    <input type="text" name="nombre" 
           value="<?php echo $fila['nombre']; ?>" required>

    <button type="submit">Actualizar</button>
</form>