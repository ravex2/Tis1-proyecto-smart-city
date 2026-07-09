<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();
$cat_pub = $db->query("SELECT * FROM categoria_reporte");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_categoria_reporte = $_POST["id_categoria_reporte"];
    $latitud = $_POST["latitud"];
    $longitud = $_POST["longitud"];
    $imagen = $_POST["imagen"];

    $consulta = "INSERT INTO 
    reporte(titulo, descripcion, id_categoria_reporte, latitud, longitud, imagen, rut_usuario, tipo_estado) 
    VALUES('$titulo', '$descripcion', '$id_categoria_reporte', '$latitud', '$longitud', '$imagen', 20630531, 'pendiente')";

    $db->query($consulta);
    header("Location: ?ruta=crear_reporte");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity</title>
    <link href="https://jsdelivr.net" rel="stylesheet">
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link rel="stylesheet" href="/TIS1-PROYECTO-SMART-CITY/assets/css/panel.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

            <main class="col-md-10 ms-sm-auto px-5 py-4">
                <div class="mb-4">
                    <h2 class="fw-bold mb-1">Ingresar Reporte Ciudadano</h2>
                    <p class="text-muted small">Complete la información para reportar un incidente directamente al municipio.</p>
                </div>

                <div class="card border-0 p-4 bg-white rounded-4 shadow-sm mb-4" style="box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04) !important;">
                    <form method="POST" class="mt-2">

                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-secondary">Título del Incidente</label>
                            <input type="text" name="titulo" class="form-control rounded-3 py-2 px-3" placeholder="Ej: Bache profundo en calzada principal / Fuga de agua" required>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Categoría del Incidente</label>
                                <select name="id_categoria_reporte" class="form-select rounded-3 py-2 px-3">
                                    <?php foreach ($cat_pub as $c) { ?>
                                        <option value="<?php echo $c['id_categoria']; ?>">
                                            <?php echo htmlspecialchars($c['nombre_categoria']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Evidencia Fotográfica </label>
                                <input type="text" name="imagen" class="form-control rounded-3 py-2 px-3" placeholder="Ej: imagen.jpg o bache.png" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-secondary">Ubicación Geográfica</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-primary px-4 fw-bold rounded-start-3" onclick="obtenerUbicacion()">
                                    <i class="bi bi-geo-alt-fill me-2"></i> Obtener ubicación
                                </button>
                                <input type="text" class="form-control bg-light rounded-end-3 border-start-0 text-muted" id="coordenadas-texto-visible" placeholder="Coordenadas no obtenidas aún" readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-secondary">Descripción Detallada del Suceso</label>
                            <textarea name="descripcion" class="form-control rounded-3 px-3 py-2" placeholder="Escriba de forma detallada el problema observado para facilitar la revisión del funcionario..." rows="4" required></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <a href="?ruta=leer_mis_reportes" class="text-muted text-decoration-none small fw-medium">
                                <i class="bi bi-arrow-left me-1"></i> Ir al listado
                            </a>
                            <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                Publicar Reporte
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="/Tis1-proyecto-smart-city/src/reportes/geolocalizacion.js"></script>
</body>

</html>