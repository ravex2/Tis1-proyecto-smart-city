<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$rut = $_SESSION['user']['rut'];
$consultaFuncionario = "SELECT id_funcionario FROM funcionario_municipal WHERE rut_usuario = ? ";
$resultado = $db->query($consultaFuncionario, [$rut]);
$funcionario = $resultado[0] ?? null;

$usuarioLogeado = $_SESSION['user'] ?? null;

if(!$funcionario){
    header("Location: ?ruta=dashboard");
    exit();
}

$id_funcionario = $funcionario['id_funcionario'];
$cat_pub = $db->query("SELECT * FROM categoria_publicacion");

if (isset($_GET["error"])) {

    if ($_GET["error"] == "fecha_pasada") {
        echo '<div class="alert alert-danger">La fecha del evento debe ser futura.</div>';
    }

    if ($_GET["error"] == "fecha_invalida") {
        echo '<div class="alert alert-danger"> La fecha ingresada no es válida. </div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $contenido = $_POST["contenido"];
    $titulo = $_POST["titulo"];
    $fecha_evento = $_POST["fecha_evento"] ?? null;
    if ($fecha_evento != "") {

        $fecha = DateTime::createFromFormat("Y-m-d\TH:i", $fecha_evento);

        if (!$fecha) {
            header("Location: ?ruta=crear_publicacion&error=fecha_invalida");
            exit();
        }

        if ($fecha < new DateTime()) {
            header("Location: ?ruta=crear_publicacion&error=fecha_pasada");
            exit();
        }
        $fecha_evento = $fecha->format("Y-m-d H:i:s");

    } else {
        $fecha_evento = null;
    }
    $tipo_estado = $_POST["tipo_estado"];
    $lugar = $_POST["lugar"];
    $imagen = $_POST["imagen"];
    $id_categoria = $_POST["id_categoria"];


    $consulta = "INSERT INTO publicacion(contenido,titulo,fecha_evento,tipo_estado,lugar,imagen, visitas, id_funcionario,id_categoria) 
    VALUES('$contenido','$titulo',".($fecha_evento ? "'$fecha_evento'" : "NULL").",'$tipo_estado','$lugar','$imagen',0,'$id_funcionario',$id_categoria)";
    
    $db->query($consulta);
    header("Location: ?ruta=crear_publicacion");

    
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
    <link rel="stylesheet" href="assets/css/panel.css">


</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>
            <main class="col-md-10 ms-sm-auto px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">Gestión de Publicaciones</h2>
                        <p class="text-muted mb-0">
                            Crea y administra las publicaciones de la municipalidad.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <a href="?ruta=crear_categoria_publicacion"
                        class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-tags-fill me-2"></i>
                            Nueva Categoría
                        </a>
                        <div class="dropdown text-end">

                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown">

                                <div class="text-end me-2">
                                    <div class="fw-semibold">
                                        <?= $usuarioLogeado['nombre'] . ' ' . $usuarioLogeado['apellido'] ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= $usuarioLogeado['correo'] ?>
                                    </small>
                                </div>

                                <img
                                    src="https://ui-avatars.com/api/?name=<?= urlencode($usuarioLogeado['nombre'].' '.$usuarioLogeado['apellido']) ?>&background=3d71ff&color=fff&rounded=true&size=40"
                                    class="rounded-circle"
                                    width="42"
                                    height="42"
                                    alt="usuario">

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item text-danger" href="?ruta=logout">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Cerrar sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 rounded-top-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-file-earmark-plus-fill text-primary me-2"></i>
                            Nueva Publicación
                        </h5>
                        <small class="text-muted">
                            Completa la información para registrar una nueva publicación.
                        </small>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">
                                        Título
                                    </label>
                                    <input
                                        type="text"
                                        name="titulo"
                                        class="form-control rounded-pill"
                                        placeholder="Ingrese el título de la publicación"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Categoría
                                    </label>
                                    <select
                                        name="id_categoria"
                                        class="form-select rounded-pill">
                                        <?php foreach($cat_pub as $c){ ?>
                                            <option value="<?= $c['id_categoria']; ?>">
                                                <?= $c['nombre']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Contenido
                                    </label>
                                    <textarea
                                        name="contenido"
                                        rows="5"
                                        class="form-control rounded-4"
                                        placeholder="Escriba el contenido de la publicación..."
                                        required></textarea>

                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Fecha del evento
                                    </label>
                                    <input
                                        type="datetime-local"
                                        name="fecha_evento"
                                        min="<?= date('Y-m-d\TH:i') ?>"
                                        class="form-control rounded-pill">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Estado
                                    </label>
                                    <select
                                        name="tipo_estado"
                                        class="form-select rounded-pill">
                                        <option value="activa">Activa</option>
                                        <option value="desactivada">No activa</option>
                                    </select>
                                </div>
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Lugar
                                    </label>

                                    <input
                                        type="text"
                                        name="lugar"
                                        class="form-control rounded-pill"
                                        placeholder="Ej. Plaza de Armas"
                                        required>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Imagen
                                    </label>

                                    <input
                                        type="text"
                                        name="imagen"
                                        class="form-control rounded-pill"
                                        placeholder="imagen_evento.jpg"
                                        required>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between align-items-center">

                                <a href="?ruta=leer_publicacion"
                                class="btn btn-outline-secondary rounded-pill px-4">

                                    <i class="bi bi-list-ul me-2"></i>
                                    Ver publicaciones

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary rounded-pill px-5">

                                    <i class="bi bi-send-fill me-2"></i>
                                    Publicar

                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"
    ></script>
</body>
</html>