<?php
require_once __DIR__ . "/../../config/database.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$rut_usuario = $_SESSION['user']['rut'];


$db = getDatabase();
$cat_pub = $db->query("SELECT * FROM categoria_reporte");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $descripcion = $_POST["descripcion"];
    $imagen = $_POST["imagen"];
    $id_categoria_reporte = $_POST["id_categoria_reporte"];
    $latitud = $_POST["latitud"];
    $longitud = $_POST["longitud"];
    

    $consulta = "INSERT INTO reporte(descripcion,id_categoria_reporte,latitud,longitud,imagen,rut_usuario) 
                VALUES('$descripcion','$id_categoria_reporte','$latitud','$longitud','$imagen',$rut_usuario)";
    $db->query($consulta);
    header("Location: ?ruta=crear_reporte");

    
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

                                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary" onclick="obtenerUbicacion()">
                                    Obtener ubicación
                                </button>

                                <input type="hidden" name="latitud" id="latitud">
                                <input type="hidden" name="longitud" id="longitud">

                                <div class="mb-3">
                                    <textarea name="descripcion" class="form-control rounded-4 px-3 py-2"
                                    placeholder="Descripcion Reporte" rows="3" required></textarea>
                                </div>


                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <input type="text" name="imagen" class="form-control rounded-pill px-3 py-2"
                                            placeholder="imagen_1.jpg" required>
                                    </div>

                                    <div class="col-md-6">
                                        <select name = "id_categoria_reporte" class="form-select rounded-pill px-3 py-2">
                                            <?php
                                                foreach($cat_pub as $c){ ?>
                                                    <option value="<?php echo $c['id_categoria']; ?>" >
                                                        <?php echo $c['nombre_categoria'];?>
                                                    </option>

                                            <?php } ?>

                                        </select>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary">
                                        Publicar
                                    </button>
                                </div>
                            </form>
                            
                        </div>

                        
                        
                    </div>
                    
                </div>
                <div class="d-flex justify-content-end">
                    <a href="?ruta=leer_mis_reportes" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ir al listado
                    </a>
                </div>
            </main>

            
        </div>
    </div>
    <script src="/Tis1-proyecto-smart-city/src/reportes/geolocalizacion.js"></script>
</body>
</html>