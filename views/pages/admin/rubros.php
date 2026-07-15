<?php
require_once __DIR__ . '/../../../controllers/rubros.controlador.php';

$controlador = new RubrosControlador();
$rubros = $controlador->obtenerRubros();
// CREAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_crear'])) {
    try {
        $datos = [
            'nombre_rubro' => $_POST['nombre_rubro'],
        ];
        $resultado = $controlador->crearRubro($datos);
        
        if ($resultado) {
            echo "<script>alert('Rubro creado con éxito'); window.location='rubros.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al crear rubro: " . $e->getMessage() . "');</script>";
    }
}
// ELIMINAR
if (isset($_POST['btn_eliminar'])) {
    try {
        $resultado = $controlador->eliminarRubro($_POST['id_rubro']);
        if ($resultado) {
            echo "<script>alert('Rubro eliminado con éxito'); window.location='rubros.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al eliminar rubro: " . $e->getMessage() . "');</script>";
    }
}
// EDITAR 
if (isset($_POST['btn_editar'])) {
    try {
        $id_rubro = $_POST['id_rubro'];
        $datos = [
            'id_rubro' => $_POST['id_rubro'],
            'nombre_rubro' => $_POST['nombre_rubro'],
        ];
        $resultado = $controlador->editarRubro($id_rubro, $datos);
        if ($resultado) {
            echo "<script>alert('Rubro editado con éxito'); window.location='rubros.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al editar rubro: " . $e->getMessage() . "');</script>";
    }
}



?>
<?php
$pageTitle = 'Gestión de Rubros';
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
        .shadow-card { box-shadow: var(--shadow-soft); border: none; border-radius: 24px; }
        
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
        .icon-shape {
            width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; font-size: 1.2rem;
        }
        .btn-action-soft {
            width: 35px; height: 35px; border-radius: 10px; border: none; 
            background: #f1f5f9; color: #64748b; transition: 0.2s;
        }
        .btn-action-soft:hover { background: #e2e8f0; color: #0f172a; }
        .modal-content { border-radius: 24px; border: none; padding: 10px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px; border: 1px solid #f1f5f9; background: #f8fafc; }
    </style>
</style>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php 
            include __DIR__ . "../../../layout/sidebar.php";
        ?>
        <main class="col-md-10 ms-sm-auto px-md-5">
            <header class="d-flex justify-content-end align-items-center py-4">
                <?php include __DIR__ . "../../../layout/panel/navbar_user_panel.php"; ?>
            </header>

            <div class="card shadow-card overflow-hidden">
                <div class="table-responsive p-4">
                    <table class="table table-custom mb-0 table-datatable">
                        <thead>
                            <tr>
                                <th>Icono</th>
                                <th>Identificador</th>
                                <th>Nombre del Rubro</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rubros)): ?>
                            <tr class="border-0">
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                    No hay rubros registrados actualmente.
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($rubros as $r): ?>
                                <tr class="border-0">
                                    <td>
                                        <div class="icon-shape bg-primary">
                                            <i class="bi bi-shop text-white"></i>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold small"><?= htmlspecialchars($r['id_rubro']) ?></span></td>
                                    <td><span class="fw-bold small"><?= htmlspecialchars($r['nombre_rubro']) ?></span></td>
                                    <td class="text-end">
    
                                        <button class="btn-action-soft me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rubroModalEditar"
                                            onclick='editar_rubro(<?= json_encode($r) ?>)'>
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn-action-soft text-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminarRubro"
                                            onclick="document.getElementById('id_rubro_eliminar').value = '<?= $r['id_rubro'] ?>'">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<!-- Modal Crear -->
<div class="modal fade" id="rubroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Configurar Rubro Comercial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="">
                    <div class="col-12">
                        <label class="form-label small fw-bold">Nombre del Rubro</label>
                        <input type="text" name="nombre_rubro" class="form-control" placeholder="Ej: Artesanía en Madera" required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" name="btn_crear" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Guardar Rubro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Editar -->
<div class="modal fade" id="rubroModalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Editar Rubro Comercial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="">
                    <input type="hidden" name="id_rubro" id="editar_id_rubro">
                    
                    <div class="col-12">
                        <label class="form-label small fw-bold">Nombre del Rubro</label>
                        <input type="text" name="nombre_rubro" id="editar_nombre_rubro" class="form-control" placeholder="Ej: Artesanía en Madera" required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" name="btn_editar" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Editar Rubro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminarRubro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold text-danger">Eliminar Rubro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>¿Estás seguro de que deseas eliminar este rubro? Esta acción no se puede deshacer.</p>
                <form method="post" class="mt-4" action="">
                    <input type="hidden" name="id_rubro" id="id_rubro_eliminar">
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-50 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="btn_eliminar" class="btn btn-danger w-50 rounded-pill fw-bold">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function editar_rubro(rubro) {
    document.getElementById('editar_id_rubro').value = rubro.id_rubro;
    document.getElementById('editar_nombre_rubro').value = rubro.nombre_rubro;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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