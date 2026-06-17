<?php
    require_once __DIR__ . "/../../config/database.php";
    $cat_pub = $conexion->query("SELECT * FROM categoria_publicacion");

    if(isset($_GET["id_enviado"])){

        $id_capturado = $_GET["id_enviado"];
        $consulta = "SELECT * FROM publicacion WHERE id_publicacion=$id_capturado";
        $resultado =$conexion->query($consulta);
        $fila = $resultado->fetch();

        if(!$fila){
            header("Location: leer_publicacion");
            exit();
        }


    }else{
        echo "No existe este ID";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $contenido = $_POST["contenido"];
    $titulo = $_POST["titulo"];
    $fecha_evento = $_POST["fecha_evento"] ?? null;
    if ($fecha_evento != "") {
        $fecha_evento = str_replace("T", " ", $fecha_evento) . ":00";
    } else {
        $fecha_evento = null;
    }
    $tipo_estado = $_POST["tipo_estado"];
    $lugar = $_POST["lugar"];
    $imagen = $_POST["imagen"];
    $id_categoria = $_POST["id_categoria"];


    $update ="UPDATE publicacion SET
    titulo = '$titulo',
    contenido = '$contenido',
    fecha_evento = ".($fecha_evento ? "'$fecha_evento'" : "NULL").",
    tipo_estado = '$tipo_estado',
    lugar = '$lugar',
    imagen = '$imagen',
    id_categoria = $id_categoria
    WHERE id_publicacion = $id_capturado";

    $resultado=$conexion->query($update);

    if($resultado){
        header("Location: leer_publicacion");
        exit();

    }else{
        echo "Error al actualizar la publicacion";
    }
    
    
    
}


?>

<h2>Editar Nombre Categoria Publicacion</h2>

<form method="POST">
    <input type="text" name="titulo" 
    value="<?php echo $fila['titulo']; ?>" required>
    
    <input type="text" name="contenido" 
    value="<?php echo $fila['contenido']; ?>" required>

    <input type="datetime-local" name="fecha_evento" 
    value="<?php echo str_replace(' ', 'T', $fila['fecha_evento']); ?>">


    <select name="tipo_estado">
        <option value="activa" <?php if($fila['tipo_estado']=="activa") echo "selected"; ?>>Activa</option>
        <option value="desactivada" <?php if($fila['tipo_estado']=="desactivada") echo "selected"; ?>>No Activa</option>
    </select>

    <input type="text" name="lugar" 
    value="<?php echo $fila['lugar']; ?>" required>

    <input type="text" name="imagen" 
    value="<?php echo $fila['imagen']; ?>" required>

    <select name = "id_categoria">
        <?php
            foreach($cat_pub as $c){ ?>
                <option value="<?php echo $c['id_categoria']; ?>" >
                    <?php echo $c['nombre'];?>
                </option>

           <?php } ?>

    </select>

    <button type="submit">Actualizar</button>
</form>