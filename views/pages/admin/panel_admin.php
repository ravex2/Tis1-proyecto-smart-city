<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ?ruta=login');
        exit();
    }


    require_once __DIR__ . '/../../../models/Area.php';
    require_once __DIR__ . '/../../../models/usuario.php';
    require_once __DIR__ . '/../../../models/publicacion.php';
    $usuarios = new Usuario();
    $publicaciones = new Publicacion();
    
    $totalUsuarios = $usuarios->countAll();
    $totalPublicaciones = $publicaciones->countAll();
    $totalDepartamentos = contarAreas();

    $usuarioLogeado = $_SESSION['user'] ?? null;    
?>

<!doctype html>
<html lang="es">
    <head>
        <title>Dashboard Administrativo - Smart City</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/css/panel.css">
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../../layout/sidebar.php"; ?>
                
                <div class="col-md-10 col-lg-10 p-4">
                    <div class="mb-3 d-flex justify-content-between align-items-center">

                        <div>
                            <h3 class="fw-bold mb-1">Panel de Administración</h3>
                            <p class="text-muted mb-0">Gestiona toda la plataforma de forma sencilla</p>
                        </div>

                        <div class="dropdown text-end">
                            
                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">

                                <div class="text-start">
                                    <div class="fw-semibold">
                                        <?= $usuarioLogeado['nombre'] . ' ' . $usuarioLogeado['apellido'] ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= $usuarioLogeado['correo'] ?>
                                    </small>
                                </div>

                                <div class="me-2">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuarioLogeado['nombre'].' '.$usuarioLogeado['apellido']) ?>&background=3d71ff&color=fff&rounded=true&size=40"
                                        class="rounded-circle"
                                        width="40"
                                        height="40"
                                        alt="usuario">
                                </div>

                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item text-danger" href="?ruta=logout">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>
                    <div class="row g-4 mb-3">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Total de usuarios</h6>
                                        <h3 class="fw-bold mb-0"><?= $totalUsuarios ?></h3>
                                    </div>

                                    <div class="text-primary fs-1">
                                        <i class="bi bi-people-fill"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Total de Publicaciones</h6>
                                        <h3 class="fw-bold mb-0"><?= $totalPublicaciones ?></h3>
                                    </div>

                                    <div class="text-primary fs-1">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">

                                    <div>
                                        <h6 class="text-muted mb-1">Total de Departamentos</h6>
                                        <h3 class="fw-bold mb-0"><?= $totalDepartamentos ?></h3>
                                    </div>

                                    <div class="text-primary fs-1">
                                        <i class="bi bi-building"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Acciones rápidas</h5>

                                    <div class="d-grid gap-2">
                                        <a href="?ruta=crear_publicacion" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Nueva publicación
                                        </a>

                                        <a href="?ruta=leer_publicacion" class="btn btn-success">
                                            <i class="bi bi-eye"></i> Ver publicaciones
                                        </a>

                                        <a href="?ruta=leer_categoria_publicacion" class="btn btn-warning">
                                            <i class="bi bi-folder-plus"></i> Categorías
                                        </a>
                                        <a href="#" class="btn btn-danger">
                                            <i class="bi bi-exclamation-triangle-fill"> Revisar Comercio Local</i> 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Revisión del sistema</h5>

                                    <div class="mb-3">
                                        <p class="text-muted mb-1">Emprendimientos en revisión</p>
                                        <h3 class="fw-bold text-warning">
                                            8
                                        </h3>
                                    </div>

                                    <div>
                                        <p class="text-muted mb-1">Reportes en revisión</p>
                                        <h3 class="fw-bold text-danger">
                                            3
                                        </h3>
                                    </div>

                                    <div class="mt-3">
                                        <span class="badge bg-warning text-dark">Pendiente moderación</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row g-4">

                        <!-- PARTICIPACIÓN EN VOTACIONES -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Participación ciudadana</h5>
                                    <canvas id="participacionChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- SECTOR MÁS PARTICIPATIVO -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Sector con mayor participación</h5>
                                    <canvas id="sectorChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            /* Participación en votaciones */
            new Chart(document.getElementById('participacionChart'), {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Participación %',
                        data: [45, 52, 60, 58, 70, 78],
                        borderColor: '#3d71ff',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(61,113,255,0.1)'
                    }]
                },
                options: {
                    responsive: true
                }
            });

            /* Sector más participativo */
            new Chart(document.getElementById('sectorChart'), {
                type: 'bar',
                data: {
                    labels: ['Centro', 'Norte', 'Sur', 'Oriente', 'Poniente'],
                    datasets: [{
                        label: 'Participación',
                        data: [65, 40, 80, 55, 70],
                        backgroundColor: [
                            '#3d71ff',
                            '#28a745',
                            '#ffc107',
                            '#dc3545',
                            '#6f42c1'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        </script>

        
    </body>
</html>
