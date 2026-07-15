<?php
require_once __DIR__ . "/../../../config/database.php";

$db = getDatabase();
$consulta = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN tipo_estado = 'pendiente' THEN 1 ELSE 0 END) as pendiente,
                SUM(CASE WHEN tipo_estado = 'rechazado' THEN 1 ELSE 0 END) as rechazado,
                SUM(CASE WHEN tipo_estado = 'en proceso' THEN 1 ELSE 0 END) as en_proceso,
                SUM(CASE WHEN tipo_estado = 'resuelto' THEN 1 ELSE 0 END) as resuelto
             FROM reporte";

$resultado = $db->query($consulta);

$consulta_lista = "SELECT r.*, c.nombre_categoria 
                    FROM reporte r
                    LEFT JOIN categoria_reporte c ON r.id_categoria_reporte = c.id_categoria
                    ORDER BY r.fecha DESC";
$resultado_lista = $db->query($consulta_lista);

?>



<?php
$pageTitle = 'Gestión de Reportes';
include __DIR__ . "../../../layout/header.php";
?>
<style>
        :root {
            --primary-blue: #3d71ff;
            --bg-light: #f8fafc;
            --sidebar-text: #64748b;
            --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #0f172a;
        }

        .sidebar {
            background: #ffffff;
            height: 100vh;
            border-right: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .nav-link {
            color: var(--sidebar-text);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .nav-link.active {
            background-color: #f0f4ff;
            color: var(--primary-blue);
        }

        /* UI Components */
        .shadow-card {
            box-shadow: var(--shadow-soft);
            border: none;
            border-radius: 24px;
        }

        /* Table Styling - border-0 en tr */
        .table-custom thead th {
            background-color: #f8fafc;
            border: 0 !important;
            color: #64748b;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 18px 24px;
        }

        .table-custom tbody tr td {
            border: 0 !important;
            padding: 18px 24px;
            vertical-align: middle;
        }

        /* Status Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pendiente {
            background: #fff7ed;
            color: #9a3412;
        }

        .status-revision {
            background: #eff6ff;
            color: #1e40af;
        }

        .status-resuelto {
            background: #f0fdf4;
            color: #166534;
        }

        .btn-action-soft {
            width: 35px;
            height: 35px;
            border-radius: 10px;
            border: none;
            background: #f1f5f9;
            color: #64748b;
            transition: 0.2s;
        }

        .btn-action-soft:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .img-report-preview {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            object-fit: cover;
        }
    </style>
</style>
<div class="container-fluid">
        <div class="row">
            
            <?php include __DIR__ . "../../../layout/sidebar.php"; ?>
            <!-- Main  -->
            <main class="col-md-10 ms-sm-auto px-md-5">
                <header class="d-flex justify-content-between align-items-center py-4">
                    <div>
                        <h2 class="fw-bold mb-0">Gestión de Reportes Ciudadanos</h2>
                        <p class="text-muted small">Administra, deriva y supervisa la resolución de incidentes en la comuna.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary rounded-pill px-3 fw-bold shadow-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </button>
                        <button class="btn btn-outline-secondary rounded-pill px-3 fw-bold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                    </div>
                </header>

                <!-- Filtros Rápidos -->
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
                        <table class="table table-custom mb-0 table-datatable">
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
                                                <a href="?ruta=ver_reporte_funcionario&id_enviado=<?php echo $reporte['id_reporte']; ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm btn-sm">
                                                    Ver reporte
                                                </a>
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

<?php include __DIR__ . "../../../layout/footer.php"; ?>

<script>
    $(document).ready(function () {
        if (typeof $.fn.DataTable === 'function') {
            $('.table-datatable').each(function () {
                const $table = $(this);
                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().clear().destroy();
                }

                $table.DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                    },
                    pageLength: 10,
                    responsive: true,
                    columnDefs: [
                        { orderable: false, targets: -1 }
                    ]
                });
            });
        }
    });
</script>
</body>
</html>