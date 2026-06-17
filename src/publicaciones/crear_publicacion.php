<?php
require_once __DIR__ . "/../../config/database.php";
$cat_pub = $conexion->query("SELECT * FROM categoria_publicacion");

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


    $consulta = "INSERT INTO publicacion(contenido,titulo,fecha_evento,tipo_estado,lugar,imagen, visitas, id_funcionario,id_categoria) 
                VALUES('$contenido','$titulo',".($fecha_evento ? "'$fecha_evento'" : "NULL").",'$tipo_estado','$lugar','$imagen',0,1,$id_categoria)";
    $conexion->query($consulta);
    header("Location: leer_publicacion");

    
}
?>

<h2>Crear Publicacion</h2>

<form method="POST">
    <input type="text" name="titulo" placeholder="Titulo" required>
    <input type="text" name="contenido" placeholder="Descripcion de la publicacion" required>
    <input type="datetime-local" name="fecha_evento">
    <select name="tipo_estado">
        <option value="activa">Activa</option>
        <option value="desactivada">No Activa</option>
    </select>
    <input type="text" name="lugar" placeholder="Lugar de el Evento" required>
    <input type="text" name="imagen" placeholder="imagen_1.jpg" required>
    <select name = "id_categoria">
        <?php
            foreach($cat_pub as $c){ ?>
                <option value="<?php echo $c['id_categoria']; ?>" >
                    <?php echo $c['nombre'];?>
                </option>

           <?php } ?>

    </select>

    <button type="submit">Publicar</button>
</form>