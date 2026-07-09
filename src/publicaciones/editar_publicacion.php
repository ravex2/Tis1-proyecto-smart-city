<?php
    require_once __DIR__ . "/../../config/database.php";
    $db = getDatabase();
    $cat_pub = $db->query("SELECT * FROM categoria_publicacion");

    if(isset($_GET["id_enviado"])){

        $id_capturado = $_GET["id_enviado"];
        $consulta = "SELECT * FROM publicacion WHERE id_publicacion=$id_capturado";
        $resultado = $db->query($consulta);
        $fila = $resultado[0] ?? null;

        if(!$fila){
            header("Location: ?ruta=leer_publicacion");
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

    $resultado=$db->execute($update);

    if($resultado){
        header("Location: ?ruta=leer_publicacion");
        exit();

    }else{
        echo "Error al actualizar la publicacion";
    }
    
    
    
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/panel.css">


</head>
<body>

    <div class="container-fluid">
    <div class="row">

        <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-4">
                <div class="feed-header p-3 sticky-top bg-white-glass blur">
                    <h5 class="fw-bold mb-0">Inicio</h5>
                </div>

                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">
                        <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle" width="48" height="48">
                        <div class="flex-grow-1">




                            <form method="POST" class="mt-2">
                                <div class="mb-3">
                                    <label>Titulo de la Publicacion</label>
                                    <input type="text" name="titulo" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $fila['titulo']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label>Contenido de la publicacion</label>
                                    <textarea name="contenido" class="form-control rounded-4 px-3 py-2" required><?php echo $fila['contenido']; ?></textarea>
                                </div>

                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <label>Fecha</label>
                                        <input type="datetime-local" name="fecha_evento"
                                        class="form-control rounded-pill px-3 py-2" value="<?php echo str_replace(' ', 'T', $fila['fecha_evento']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Estado Publicacion</label>
                                        <select name="tipo_estado" class="form-select rounded-pill px-3 py-2">
                                            <option value="activa" <?php if($fila['tipo_estado']=="activa") echo "selected"; ?>>Activa</option>
                                            <option value="desactivada" <?php if($fila['tipo_estado']=="desactivada") echo "selected"; ?>>No Activa</option>
                                        </select>
                                    </div>

                                </div>
        
                                <div class="mb-3">
                                    <label>Lugar</label>
                                    <input type="text" name="lugar" class="form-control rounded-pill px-3 py-2"
                                    value="<?php echo $fila['lugar']; ?>" required>
                                </div>

                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <label>Imagen</label>
                                        <input type="text" name="imagen" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $fila['imagen']; ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Categoria</label>
                                        <select name = "id_categoria" class="form-select rounded-pill px-3 py-2">
                                            <?php
                                                foreach($cat_pub as $c){ ?>
                                                    <option value="<?php echo $c['id_categoria']; ?>"
                                                     <?php if($fila['id_categoria'] == $c['id_categoria']) echo "selected"; ?>>
                                                        <?php echo $c['nombre'];?>
                                                    </option>

                                            <?php } ?>

                                        </select>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary">
                                        Actualizar
                                    </button>
                                </div>
                            </form>
                            
                        </div>

                        
                        
                    </div>
                    
                </div>
                <div class="d-flex justify-content-end">
                    <a href="?ruta=crear_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Volver a Publicacion
                    </a>
                    <a href="leer_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ir al listado
                    </a>
                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>
