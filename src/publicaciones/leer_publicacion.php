<?php
    require_once __DIR__ . "/../../config/database.php";

    $consulta = "SELECT p.*, c.nombre AS categoria_nombre FROM publicacion p
    JOIN categoria_publicacion c ON p.id_categoria = c.id_categoria ORDER BY fecha DESC"; ;
    $db = getDatabase();
    $resultado =$db->query($consulta);
    $usuarioLogeado = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity- Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/panel.css">


</head>
<body>

    <div class="container-fluid">
        <div class="row">

            <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

            <main class="col-md-10 ms-sm-auto px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold mb-1">Listado Publicaciones</h2>
                        <p class="text-muted mb-0">
                            Gestiona las publicaciones creadas.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-3">

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
                <?php if(count($resultado) > 0){ ?>
                <?php   foreach($resultado as $fila){?>
                                <div class="card mb-4 shadow-sm border-0 rounded-4">

                                    <div class="card-body">

                                        <h4 class="fw-bold"><?php echo $fila['titulo']; ?></h4>

                                        <span class="badge bg-primary mb-2">
                                            <?php echo $fila['categoria_nombre']; ?>
                                        </span>

                                        <div class="mb-3">
                                            <img src="../src/publicaciones/<?php echo $fila['imagen']; ?>" 
                                                class="img-fluid rounded-3" style="max-height:300px; object-fit:cover;">
                                        </div>

                                        <p class="text-muted"><?php echo $fila['contenido']; ?></p>

                                        <p class="small text-secondary mb-2">
                                            <?php echo $fila['lugar']; ?> |
                                            <?php 
                                            if (!empty($fila['fecha_evento'])) {
                                                echo date("d-m-Y", strtotime($fila['fecha_evento']));
                                            } else {
                                                echo "Sin fecha";
                                            }
                                            ?>
                                        </p>

                                        <div class="d-flex gap-2">

                                            <a href="?ruta=editar_publicacion&id_enviado=<?php echo $fila['id_publicacion']; ?>" 
                                            class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary">
                                                Editar
                                            </a>

                                            <a href="?ruta=eliminar_publicacion&id_enviado=<?php echo $fila['id_publicacion']; ?>" 
                                            class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-primary">
                                                Eliminar
                                            </a>

                                        </div>

                                    </div>

                                </div>

                    <?php  }?>
                <?php }?>
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
