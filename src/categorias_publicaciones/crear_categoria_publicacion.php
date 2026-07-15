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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    if (!empty($nombre)) {
        $resultado = $db->execute(
            "INSERT INTO categoria_publicacion (nombre, id_funcionario) VALUES(?,?)",
            [$nombre, $id_funcionario]
        );

        if ($resultado) {
            header("Location: ?ruta=crear_publicacion");
            exit();
        } else {
            echo "Error al crear";
        }

    } else {
        echo "La categoria nesesita un nombre";
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
    <link rel="stylesheet" href="assets/css/panel.css">


</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

            <main class="col-md-10 ms-sm-auto px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">Crear Categoría</h2>
                        <p class="text-muted mb-0">
                            Agrega una nueva categoría para organizar las publicaciones.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-3">

                        <a href="?ruta=leer_categoria_publicacion"
                        class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-list-ul me-2"></i>
                            Ver Categorías
                        </a>

                        <!-- Usuario logueado -->
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
                                    <a class="dropdown-item text-danger"
                                    href="?ruta=logout">

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
                            <i class="bi bi-tags-fill text-primary me-2"></i>
                            Nueva Categoría
                        </h5>

                        <small class="text-muted">
                            Completa el siguiente campo para registrar una nueva categoría.
                        </small>

                    </div>

                    <div class="card-body p-4">

                        <form method="POST">

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Nombre de la categoría
                                </label>

                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control rounded-pill"
                                    placeholder="Ej. Deportes, Cultura, Eventos..."
                                    required>

                            </div>

                            <div class="d-flex justify-content-between">

                                <a href="?ruta=crear_publicacion"
                                class="btn btn-outline-secondary rounded-pill px-4">

                                    <i class="bi bi-arrow-left me-2"></i>
                                    Volver

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary rounded-pill px-5">

                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Guardar Categoría

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </main>

        </div>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"
    ></script>
</body>

</html>