<?php
    if (!isset($_SESSION['user'])) {
        header('Location: ?ruta=login');
        exit();
    }

    require_once __DIR__ . '/../../../models/Area.php';
    require_once __DIR__ . '/../../../models/usuario.php';
    require_once __DIR__ . '/../../../models/publicacion.php';
    require_once __DIR__ . "/../../../config/database.php";
    $db = getDatabase();

    $usuarios = new Usuario();
    $publicaciones = new Publicacion();
    
    $totalUsuarios = $usuarios->countAll();
    $totalPublicaciones = $publicaciones->countAll();
    $totalDepartamentos = contarAreas();

    $emprendimientosEnRevision = $db->query("SELECT COUNT(*) as total FROM negocio_local WHERE tipo_estado = 'pendiente a aprobacion'")[0]['total'] ?? 0;
    $reportesEnRevision = $db->query("SELECT COUNT(*) as total FROM reporte WHERE tipo_estado = 'pendiente'")[0]['total'] ?? 0;

    $usuarioLogeado = $_SESSION['user'] ?? null;

    // traer la informacion de analiticas:
    require_once __DIR__ . '/../../../controllers/analiticas.controlador.php';
    $analiticasController = new AnaliticasController();
    $rangoFechas = $analiticasController->resolverRangoFechas(
        $_GET['fecha_desde'] ?? null,
        $_GET['fecha_hasta'] ?? null
    );


    $analiticasData = $analiticasController->obtenerDatosGraficos(
        $rangoFechas['desde'],
        $rangoFechas['hasta']
    );

?>

<!doctype html>
<html lang="es">
    <head>
        <title>Dashboard Administrativo - Smart City</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="assets/css/panel.css">
        <link rel="stylesheet" href="assets/css/panel_admin.css">
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
                                        <?= htmlspecialchars($usuarioLogeado['nombre'] . ' ' . $usuarioLogeado['apellido']) ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($usuarioLogeado['correo']) ?>
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

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-1">Analíticas de participación</h5>
                                            <p class="text-muted mb-0 small">Filtra por rango de fechas para actualizar los gráficos</p>
                                        </div>
                                    </div>

                                </div>
                                <!--
                                <div class="col-6">
                                    <div class="d-flex justify-content-end gap-2">
                                        <div class="dropdown">
                                            <button class="btn btn-white shadow-sm rounded-pill px-3 dropdown-toggle btn-sm" type="button">
                                                <i class="bi bi-calendar3 me-2"></i> Período: Abril 2026
                                            </button>
                                        </div>
                                        <button class="btn btn-primary rounded-pill px-4 btn-sm shadow-primary"><i class="bi bi-file-earmark-pdf me-2"></i> Exportar PDF</button>
                                        <button class="btn btn-success text-white rounded-pill px-4 btn-sm shadow-sm" style="background-color: #10b981; border: none;"><i class="bi bi-file-earmark-excel me-2"></i> Excel</button>
                                    </div>
                                </div>
                                -->
                            </div>
  

                         <form id="filtroAnaliticas" action="" method="GET" class="row g-3 align-items-end">
                                <!-- Este campo oculto evita que se pierda la vista actual al filtrar -->
                                <input type="hidden" name="ruta" value="<?= htmlspecialchars($_GET['ruta'] ?? 'dashboard') ?>">
                                
                                <div class="col-md-4">
                                    <label for="fecha_desde" class="form-label">Desde</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="fecha_desde"
                                        name="fecha_desde"
                                        value="<?= htmlspecialchars($rangoFechas['desde']) ?>"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="fecha_hasta" class="form-label">Hasta</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="fecha_hasta"
                                        name="fecha_hasta"
                                        value="<?= htmlspecialchars($rangoFechas['hasta']) ?>"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel"></i> Filtrar
                                    </button>
                                </div>
                            </form>


                        </div>
                    </div>


                    <div class="row g-4 mb-3">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-card p-3 rounded-4">
                                <div class="d-flex justify-content-between text-muted mb-2">
                                    <span class="small fw-semibold">Emprendimientos en Revisión</span>
                                    <i class="bi bi-exclamation-triangle-fill text-warning opacity-50"></i>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0"><?= $emprendimientosEnRevision ?></h3>
                                </div>
                                <p class="text-muted tiny mt-2 mb-0"> pendientes de revisión</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-card p-3 rounded-4">
                                <div class="d-flex justify-content-between text-muted mb-2">
                                    <span class="small fw-semibold">Total de usuarios</span>
                                    <i class="bi bi-check2-square text-info opacity-50"></i>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0"><?= $totalUsuarios ?></h3>
                                </div>
                                <p class="text-muted tiny mt-2 mb-0">Usuarios creados</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-0 shadow-card p-3 rounded-4">
                                <div class="d-flex justify-content-between text-muted mb-2">
                                    <span class="small fw-semibold">Total de Publicaciones</span>
                                    <i class="bi bi-check2-square text-info opacity-50"></i>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0"><?= $totalPublicaciones ?></h3>
                                </div>
                                <p class="text-muted tiny mt-2 mb-0">notificas y encuestas</p>
                            </div>
                        </div>

                       <div class="col-md-3">
                            <div class="card border-0 shadow-card p-3 rounded-4">
                                <div class="d-flex justify-content-between text-muted mb-2">
                                    <span class="small fw-semibold">Total de Departamentos</span>
                                    <i class="bi bi-check2-square text-info opacity-50"></i>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <h3 class="fw-bold mb-0"><?= $totalDepartamentos ?></h3>
                                </div>
                                <p class="text-muted tiny mt-2 mb-0">Departamentos</p>
                            </div>
                        </div>

                    </div>

                    <div class="row g-4 mb-3">

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Sector con mayor participación</h5>
                                    <canvas id="participacionChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Acciones rápidas</h5>
                                    <div class="d-grid gap-2">
                                        <a href="?ruta=crear_publicacion" class="btn border-primary rapida">
                                            <i class="bi bi-plus-circle"> Nueva publicación</i> 
                                        </a>
                                        <a href="?ruta=leer_publicacion" class="btn border-primary rapida">
                                            <i class="bi bi-eye"> Ver publicaciones</i> 
                                        </a>
                                        <a href="?ruta=leer_categoria_publicacion" class="btn border-primary rapida">
                                            <i class="bi bi-folder-plus"> Categorías</i> 
                                        </a>
                                        <a href="?ruta=gestion_comercio" class="btn border-primary rapida">
                                            <i class="bi bi-exclamation-triangle-fill">  Revisar Comercio Local</i> 
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
                                        <h3 class="fw-bold <?= $emprendimientosEnRevision > 0 ? 'text-danger' : 'text-secondary' ?>">
                                            <?= $emprendimientosEnRevision ?>
                                        </h3>
                                    </div>

                                    <div>
                                        <p class="text-muted mb-1">Reportes en revisión</p>
                                        <h3 class="fw-bold <?= $reportesEnRevision > 0 ? 'text-danger' : 'text-secondary' ?>">
                                            <?= $reportesEnRevision ?>
                                        </h3>
                                    </div>

                                    <div class="mt-3">
                                        <?php if ($emprendimientosEnRevision > 0 || $reportesEnRevision > 0): ?>
                                            <span class="badge bg-warning text-dark">Pendiente moderación</span>
                                        <?php else: ?>
                                            <span class="badge bg-success text-white">Sistema al día</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Participación ciudadana</h5>
                                    <canvas id="participacionChart"></canvas>
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
            window.analiticasData = <?= json_encode($analiticasData, JSON_UNESCAPED_UNICODE) ?>;
        </script>
        <script>

            document.addEventListener('DOMContentLoaded', function() {
                // 1. Verificar que los datos existan
                if (typeof window.analiticasData === 'undefined') {
                    console.error('Error: No se encontraron los datos de analíticas (window.analiticasData)');
                    return;
                }

                const data = window.analiticasData;
                console.log(data);



                const ctxTendencia = document.getElementById('tendenciaChart');
                // Asegúrate de que tu PHP devuelva: data.evolucionMensual.labels y data.evolucionMensual.valores
                if (ctxTendencia && data.evolucionMensual) {
                    new Chart(ctxTendencia, {
                        type: 'line',
                        data: {
                            labels: data.evolucionMensual.labels, // Ej: ['Ene', 'Feb', 'Mar', ...]
                            datasets: [{
                                label: 'Participación %',
                                data: data.evolucionMensual.valores, // Ej: [45, 52, 60, ...]
                                borderColor: '#3d71ff',
                                backgroundColor: 'rgba(61, 113, 255, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#3d71ff',
                                pointBorderWidth: 2,
                                pointRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Participación: ' + context.parsed.y + '%';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100, // Asumiendo que es porcentaje
                                    ticks: { callback: function(value) { return value + '%'; } }
                                }
                            }
                        }
                    });
                } else {
                    console.log("entro aqui");
                    console.warn('Gráfico de Tendencia: Faltan datos (data.evolucionMensual) o el canvas no existe.');
                }




                // ==========================================
                // 2. GRÁFICO DE PARTICIPACIÓN CIUDADANA (Barras)
                // ==========================================
                
                const ctxParticipacion = document.getElementById('participacionChart');
                if (ctxParticipacion && data.participacionCiudadana) {
                    new Chart(ctxParticipacion, {
                        type: 'bar',
                        data: {
                            labels: data.participacionCiudadana.labels,
                            datasets: [{
                                label: 'Participantes',
                                data: data.participacionCiudadana.valores,
                                backgroundColor: 'rgba(61, 113, 255, 0.7)', // Color primario de tu diseño
                                borderColor: 'rgba(61, 113, 255, 1)',
                                borderWidth: 1,
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        afterLabel: function(context) {
                                            // Muestra el porcentaje en el tooltip
                                            const porcentajes = data.participacionCiudadana.porcentajes;
                                            return 'Porcentaje del total: ' + porcentajes[context.dataIndex] + '%';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 }
                                }
                            }
                        }
                    });
                } else {
                    console.warn('No se pudo renderizar el gráfico de participación. Verifica el ID del canvas o los datos.');
                }

                // ==========================================
                // 3. GRÁFICO DE PARTICIPACIÓN POR SECTOR (Doughnut)
                // ==========================================

                

            
            });
            

        </script>
    </body>
</html>





