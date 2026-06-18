<?php
require_once __DIR__ . '../../../../controllers/sector.controlador.php';

$controlador = new SectorControlador();
$sector = $controlador->obtenerSectores();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_crear'])) {
    try {
        $datos = [
            'nombre'      => $_POST['nombre'],
            'id_municipalidad'   => $_POST['id_municipalidad']
        ];

        $resultado = $controlador->crearSector($datos);
        
        if ($resultado) {
            echo "<script>alert('Usuario creado con éxito'); window.location='usuario.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al crear usuario: " . $e->getMessage() . "');</script>";
    }
}

// ELIMINAR
if (isset($_POST['btn_eliminar'])) {
    $controlador->eliminarSector($_POST['id_sector']);
    //header("Location: usuario.php?status=deleted");
}

// EDITAR 
if (isset($_POST['btn_editar'])) {
    echo "Entro aqui";
    $id_sector = $_POST['id_sector'];
    $datos = [
        'id_sector'   => $_POST['id_sector'],
        'nombre'      => $_POST['nombre'],
        'id_municipalidad'   => $_POST['id_municipalidad']
    ];
    $controlador->editarSector($id_sector, $datos);
    //header("Location: usuario.php?status=updated");
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Territorial - SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
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
        
        /* Table Styling - border-0 en tr solicitado */
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

        .icon-territory {
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px; font-size: 1.1rem;
            background: #f1f5f9; color: #475569;
        }

        .btn-action-soft {
            width: 35px; height: 35px; border-radius: 10px; border: none; 
            background: #f1f5f9; color: #64748b; transition: 0.2s;
        }
        .btn-action-soft:hover { background: #e2e8f0; color: #0f172a; }

        .badge-type {
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
            padding: 5px 10px; border-radius: 6px; letter-spacing: 0.02em;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
         <?php include __DIR__ . "../../../layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-md-5">
            <header class="d-flex justify-content-between align-items-center py-4">
                <div>
                    <h2 class="fw-bold mb-0">Gestión de Sectores y Barrios</h2>
                    <p class="text-muted small">Configura la división territorial para el análisis de participación ciudadana.</p>
                </div>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#sectorModal">
                    <i class="bi bi-plus-lg me-2"></i> Nuevo Sector
                </button>
            </header>

            <div class="card shadow-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Identificador</th>
                                <th>Nombre del Área</th>
                                <th>Municipalidad</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sector as $s): ?>
                            
                            <tr>
                                
                                <td class="border-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded me-3">
                                            <i class="bi bi-houses"></i>
                                        </div>
                                        <div>
                                            <p class="fw-bold mb-0 small"><?= $s['nombre'] ?></p>
                                            <p class="text-muted tiny mb-0">ID: <?= $s['id_sector'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <span class="badge-dept"><?= $s['nombre'] ?></span>
                                </td>
                                <td class="border-0">
                                    <span class="badge-dept"><?= $s['id_municipalidad'] ?></span>
                                </td>
                                <td class="border-0 text-end">
                                    <button class="btn-action me-1 border-0"  
                                        data-bs-toggle="modal" 
                                        data-bs-target="#sectorModalEditar" 
                                        onclick="editar_sector('<?= $s['id_sector'] ?>', '<?= $s['nombre'] ?>', '<?= $s['id_municipalidad'] ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn-action text-danger border-0"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminarSector" 
                                        onclick="document.getElementById('id_sector_eliminar').value = '<?= $s['id_sector'] ?>'">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="sectorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 border-0 shadow-lg" style="border-radius: 28px;">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Configurar División Territorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" accion="#">
                    <div class="col-12">
                        <label class="form-label small fw-bold">Nombre del Sector/Barrio</label>
                        <input type="text" name="nombre" class="form-control border-light bg-light rounded-3" placeholder="Ej: Villa Los Jardines">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Municipalidad</label>
                        <select name="id_municipalidad" class="form-select border-light bg-light rounded-3">
                            <option value="1"> Concepcion</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" name="btn_crear" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Registrar Sector</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="sectorModalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 border-0 shadow-lg" style="border-radius: 28px;">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Configurar División Territorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" accion="#">
                    <div class="col-3">
                        <label class="form-label small fw-bold">Id sector</label>
                        <input type="text" name="id_sector" id="editar_id_sector"  class="form-control border-light bg-light rounded-3">
                    </div>

                    <div class="col-9">
                        <label class="form-label small fw-bold">Nombre del Sector/Barrio</label>
                        <input type="text" name="nombre" id="editar_nombre"  class="form-control border-light bg-light rounded-3" placeholder="Ej: Concepcion">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Municipalidad</label>
                        <select name="id_municipalidad" id="editar_id_municipalidad" class="form-select border-light bg-light rounded-3">
                            <option value="1"> Concepcion</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" name="btn_editar" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Editar Sector</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalEliminarSector" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 border-0 shadow-lg" style="border-radius: 28px;">
            <div class="modal-header border-0">
                <h5 class="fw-bold text-danger">Eliminar Sector</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>¿Estás seguro de que deseas eliminar este sector? Esta acción no se puede deshacer.</p>
                <form method="post" class="mt-4">
                    <input type="hidden" name="id_sector" id="id_sector_eliminar">
                    
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

function editar_sector(id_sector,nombre, id_municipalidad) {
    console.log("Mostrando la infor");
    console.log(id_sector);
    document.getElementById('editar_id_sector').value = id_sector;
    document.getElementById('editar_nombre').value = nombre;
    document.getElementById('editar_id_municipalidad').value = id_municipalidad;
}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>