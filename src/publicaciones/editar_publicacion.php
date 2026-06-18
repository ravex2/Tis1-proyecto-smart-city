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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="">


    <style>

        :root {
            --primary-blue: #3d71ff;
            --bg-main: #f8fafc;
            --border-color: #f1f5f9;
            --shadow-primary: 0 4px 14px rgba(61, 113, 255, 0.3);
        }

        body {
            background-color: var(--bg-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
        }

        .custom-container {
            max-width: 1200px;
        }

        /* Sidebar Navigation */
        .nav-link {
            color: #1a1d23;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 100px;
            padding: 10px 20px;
            transition: background 0.2s;
            width: fit-content;
        }

        .nav-link i {
            font-size: 1.4rem;
        }

        .nav-link:hover {
            background-color: #eef2ff;
            color: var(--primary-blue);
        }

        .nav-link.active {
            font-weight: 700;
            color: var(--primary-blue);
        }

        /* Central Feed */
        .bg-white-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .feed-header {
            z-index: 100;
            border-bottom: 1px solid var(--border-color);
        }

        .post {
            transition: background 0.2s;
            cursor: pointer;
        }

        .post:hover {
            background-color: #f9fafb;
        }

        textarea:focus {
            box-shadow: none !important;
        }

        .post-icons i {
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: 0.2s;
        }

        .post-icons i:hover {
            background-color: #eef2ff;
        }

        /* Right Side Trends */
        .trend-card {
            border: 1px solid #edf2f7;
        }

        .hover-effect:hover {
            background-color: #edf2f7;
            cursor: pointer;
        }

        .user-profile-btn {
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-profile-btn:hover {
            background-color: #edf2f7;
        }

        .shadow-primary {
            box-shadow: var(--shadow-primary);
        }

        .tiny {
            font-size: 0.7rem;
        }

        /* Action Items Hover Colors */
        .action-item:hover { color: var(--primary-blue); }
        .text-danger-hover:hover { color: #ef4444 !important; }

    </style>

</head>
<body>

    <div class="container-fluid">
        <div class="row gx-4">
            <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

            <main class="col-md-10 border-start border-end px-0 bg-white shadow-sm min-vh-100">
                <div class="feed-header p-3 sticky-top bg-white-glass blur">
                    <h5 class="fw-bold mb-0">Inicio</h5>
                </div>

                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">
                        <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle" width="48" height="48">
                        <div class="flex-grow-1">




                            <form method="POST" class="mt-2">
                                <div class="mb-3">
                                    <input type="text" name="titulo" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $fila['titulo']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <textarea name="contenido" class="form-control rounded-4 px-3 py-2" required><?php echo $fila['contenido']; ?></textarea>
                                </div>

                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <input type="datetime-local" name="fecha_evento"
                                        class="form-control rounded-pill px-3 py-2" value="<?php echo str_replace(' ', 'T', $fila['fecha_evento']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <select name="tipo_estado" class="form-select rounded-pill px-3 py-2">
                                            <option value="activa" <?php if($fila['tipo_estado']=="activa") echo "selected"; ?>>Activa</option>
                                            <option value="desactivada" <?php if($fila['tipo_estado']=="desactivada") echo "selected"; ?>>No Activa</option>
                                        </select>
                                    </div>

                                </div>
        
                                <div class="mb-3">
                                    <input type="text" name="lugar" class="form-control rounded-pill px-3 py-2"
                                    value="<?php echo $fila['lugar']; ?>" required>
                                </div>

                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <input type="text" name="imagen" class="form-control rounded-pill px-3 py-2"
                                            value="<?php echo $fila['imagen']; ?>" required>
                                    </div>

                                    <div class="col-md-6">
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
                    <a href="leer_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ir al listado
                    </a>
                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>
