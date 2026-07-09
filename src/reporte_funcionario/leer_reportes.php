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

if(!$funcionario){
    header("Location: ?ruta=dashboard");
    exit();
}

$id_funcionario = $funcionario['id_funcionario'];
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
            <!-- Main  -->
            <main class="col-md-10 ms-sm-auto px-md-5">
                <header class="d-flex justify-content-between align-items-center py-4">
                    <div>
                        <h2 class="fw-bold mb-0">Gestión de Reportes Ciudadanos</h2>
                        <p class="text-muted small">Administra, deriva y supervisa la resolución de incidentes en la comuna.</p>
                    </div>

                </header>
                <div class="d-flex justify-content-end">
                    
                    <a href="?ruta=crear_categoria_reporte" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Crear Categoria Reporte
                    </a>
                </div>
                <div class="row mb-4 g-3">

                    <!-- Total Reportes -->
                    <div class="col-md">
                        <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                                <i class="bi bi-list-task fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0"><?php echo $resultado[0]['total']; ?></h4>
                                <span class="text-muted tiny">Total Reportes</span>
                            </div>
                        </div>
                    </div>
                    <!-- Pendientes -->
                    <div class="col-md">
                        <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning p-3 rounded-4 me-3">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0"><?php echo $resultado[0]['pendiente']; ?></h4>
                                <span class="text-muted tiny">Pendientes</span>
                            </div>
                        </div>
                    </div>
                    <!-- Rechazados -->
                    <div class="col-md">
                        <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger p-3 rounded-4 me-3">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0"><?php echo $resultado[0]['rechazado']; ?></h4>
                                <span class="text-muted tiny">Rechazados</span>
                            </div>
                        </div>
                    </div>

                    <!-- En Proceso -->
                    <div class="col-md">
                        <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                            <div class="icon-box bg-info bg-opacity-10 text-info p-3 rounded-4 me-3">
                                <i class="bi bi-gear fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0"><?php echo $resultado[0]['en_proceso']; ?></h4>
                                <span class="text-muted tiny">En Proceso</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resueltos -->
                    <div class="col-md">
                        <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                            <div class="icon-box bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                                <i class="bi bi-check2-all fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0"><?php echo $resultado[0]['resuelto']; ?></h4>
                                <span class="text-muted tiny">Resueltos</span>
                            </div>
                        </div>
                    </div>
                </div>






                <div class="card shadow-card overflow-hidden mb-5">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Título del Reporte</th>
                                    <th>Estado</th>
                                    <th>Fecha de Reporte</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if (!empty($resultado_lista) && count($resultado_lista) > 0):

                                    foreach ($resultado_lista as $reporte):

                                        $clase_badge = 'status-pendiente';
                                        switch (trim(strtolower($reporte['tipo_estado']))) {
                                            case 'pendiente':
                                                $clase_badge = 'status-pendiente bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25';
                                                break;
                                            case 'en proceso':
                                                $clase_badge = 'status-en_proceso bg-info bg-opacity-10 text-info-emphasis border border-info border-opacity-25';
                                                break;
                                            case 'resuelto':
                                                $clase_badge = 'status-resuelto bg-success bg-opacity-10 text-success-emphasis border border-success border-opacity-25';
                                                break;
                                            case 'rechazado':
                                                $clase_badge = 'status-rechazado bg-danger bg-opacity-10 text-danger-emphasis border border-danger border-opacity-25';
                                                break;
                                        }
                                ?>
                                        <tr class="border-0">

                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <?php if (!empty($reporte['imagen'])): ?>
                                                        <img src="<?php echo htmlspecialchars($reporte['imagen']); ?>" alt="Reporte" class="me-3 rounded-3 object-fit-cover" style="width: 50px; height: 50px;">
                                                    <?php else: ?>
                                                        <div class="img-report-preview me-3 bg-secondary bg-opacity-10 text-secondary" style="width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi bi-image text-muted fs-5"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div>

                                                        <div class="fw-bold small text-wrap" style="max-width: 350px;">
                                                            <?php echo htmlspecialchars($reporte['titulo']); ?>
                                                        </div>

                                                        <div class="text-muted tiny">
                                                            <?php echo !empty($reporte['nombre_categoria']) ? htmlspecialchars($reporte['nombre_categoria']) : 'Sin Categoría'; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>


                                            <td>
                                                <span class="badge-status <?php echo $clase_badge; ?>">
                                                    <?php echo htmlspecialchars($reporte['tipo_estado']); ?>
                                                </span>
                                            </td>


                                            <td class="small text-muted">
                                                <?php echo date("d M, Y", strtotime($reporte['fecha'])); ?>
                                            </td>


                                            <td class="text-end">
                                                <div class="d-flex justify-content-end align-items-center gap-1">
                                                    <!-- Botón para ver el reporte en el sistema -->
                                                    <a href="?ruta=ver_reporte_funcionario&id_enviado=<?php echo $reporte['id_reporte']; ?>"
                                                        class="btn btn-primary rounded-pill px-3 fw-bold shadow-sm btn-sm">
                                                        Ver reporte
                                                    </a>

                                                    <!-- Botón para Exportar la línea actual a PDF-->
                                                    <a href="src/reporte_funcionario/exportar_pdf.php?id=<?php echo $reporte['id_reporte']; ?>"
                                                        class="btn btn-outline-danger btn-action-soft d-flex align-items-center justify-content-center"
                                                        target="_blank"
                                                        title="Exportar esta fila a PDF">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>

                                                    <!-- Botón para Exportar la línea actual a Excel -->
                                                    <a href="src/reporte_funcionario/exportar_excel.php?id=<?php echo $reporte['id_reporte']; ?>"
                                                        class="btn btn-outline-success btn-action-soft d-flex align-items-center justify-content-center"
                                                        title="Exportar esta fila a Excel">
                                                        <i class="bi bi-file-earmark-excel"></i>
                                                    </a>
                                                </div>
                                            </td>


                                        </tr>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No se encontraron reportes registrados actualmente.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>









            </main>

            
        </div>
    </div>
    
</body>
</html>
