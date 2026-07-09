<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../../../controllers/negocio.controlador.php';
    require_once __DIR__ . '/../../../config/database.php';
    $negocios = new NegocioController;
    $emprendimientos = $negocios->listaNegocios();
    $db = getDatabase();
    $usuarioLogeado = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Comercios Locales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <h3 class="fw-bold mb-1">Gestión de Comercio Local</h3>
                    <small class="text-muted">Moderación y administración de solicitudes</small>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
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
                </div>
            </div> 

            <div class="border rounded shadow-sm bg-white p-3">
                <?php if (empty($emprendimientos)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-exclamation-circle fs-3"></i>
                        <p class="mt-2 mb-0">No hay solicitudes registradas.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Emprendimiento</th>
                                    <th>Rubro / Sector</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprendimientos as $negocio): 
                                    $id = $negocio['id_negocio'];
                                    $imgData = $db->query("SELECT ruta_imagen FROM imagenes_negocios WHERE id_negocio = ?", [$id]);
                                    $arrayImagenes = array_column($imgData, 'ruta_imagen');
                                ?>
                                    <tr>
                                        <td>#<?= $id ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($negocio['nombre']) ?></strong>
                                            <div class="text-muted small"><?= htmlspecialchars($negocio['correo_electronico']) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?= htmlspecialchars($negocio['rubro'] ?? 'Sin rubro') ?></span>
                                            <small class="text-muted d-block mt-1"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($negocio['sector'] ?? 'General') ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                                $estado = $negocio['tipo_estado'] ?? 'pendiente de aprobacion';
                                                if ($estado === 'aprobado') echo '<span class="badge bg-success">Aprobado</span>';
                                                elseif ($estado === 'rechazado') echo '<span class="badge bg-danger">Rechazado</span>';
                                                else echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" 
                                                    class="btn btn-dark btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalGestionar"
                                                    data-id="<?= $id ?>"
                                                    data-nombre="<?= htmlspecialchars($negocio['nombre']) ?>"
                                                    data-direccion="<?= htmlspecialchars($negocio['direccion']) ?>"
                                                    data-correo="<?= htmlspecialchars($negocio['correo_electronico']) ?>"
                                                    data-whatsapp="<?= htmlspecialchars($negocio['whatsapp']) ?>"
                                                    data-facebook="<?= htmlspecialchars($negocio['facebook']) ?>"
                                                    data-instagram="<?= htmlspecialchars($negocio['instagram']) ?>"
                                                    data-descripcion="<?= htmlspecialchars($negocio['descripcion'] ?? 'Sin descripción.') ?>"
                                                    data-horario="<?= htmlspecialchars(($negocio['dias_abierto'] ?? '').' de '.($negocio['hora_apertura'] ?? '').' a '.($negocio['hora_cierre'] ?? '')) ?>"
                                                    data-imagenes='<?= json_encode($arrayImagenes) ?>'>
                                                <i class="bi bi-gear-fill"></i> Gestionar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>    
        </div>
    </div>
</div>

<div class="modal fade" id="modalGestionar" tabindex="-1" aria-labelledby="modalGestionarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalGestionarLabel">Revisión de Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="?ruta=actualizar_revision" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_negocio" id="modal-id">

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">Nombre del Negocio</label>
                            <p id="modal-nombre" class="fs-5 fw-semibold text-dark mb-0"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">Contacto</label>
                            <div id="modal-contacto" class="small"></div>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold text-muted small">Dirección y Horarios</label>
                            <p class="mb-1" id="modal-direccion"></p>
                            <small class="text-muted d-block" id="modal-horario"></small>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold text-muted small">Descripción</label>
                            <p class="border rounded p-2 bg-light small" id="modal-descripcion"></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold text-muted small d-block mb-2">Imágenes Adjuntas</label>
                        <div id="modal-galeria" class="d-flex flex-wrap gap-2">
                            </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Resolución del Administrador</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_estado" id="radioAprobar" value="aprobado" checked>
                                <label class="form-check-label text-success fw-bold" for="radioAprobar">
                                    <i class="bi bi-check-circle"></i> Aprobar Emprendimiento
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_estado" id="radioRechazar" value="rechazado">
                                <label class="form-check-label text-danger fw-bold" for="radioRechazar">
                                    <i class="bi bi-x-circle"></i> Rechazar Emprendimiento
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="wrapper-observacion" style="display: none;">
                        <label for="observacion" class="form-label fw-semibold text-danger">Motivo de Rechazo (Observación)</label>
                        <textarea class="form-control" name="observacion" id="observacion" rows="3" placeholder="Escribe detalladamente por qué no se aprobó la solicitud..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Decisión</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"
></script>

<script>
    // Lógica para rellenar el Modal automáticamente con los datos del botón cliqueado
    const modalGestionar = document.getElementById('modalGestionar');
    modalGestionar.addEventListener('show.bs.modal', function (event) {
        const boton = event.relatedTarget; // Botón que gatilló el modal
        
        // Extraemos la información de los atributos 'data-*'
        const id = boton.getAttribute('data-id');
        const nombre = boton.getAttribute('data-nombre');
        const direccion = boton.getAttribute('data-direccion');
        const correo = boton.getAttribute('data-correo');
        const whatsapp = boton.getAttribute('data-whatsapp');
        const facebook = boton.getAttribute('data-facebook');
        const instagram = boton.getAttribute('data-instagram');
        const descripcion = boton.getAttribute('data-descripcion');
        const horario = boton.getAttribute('data-horario');
        const imagenes = JSON.parse(boton.getAttribute('data-imagenes') || '[]');

        // Inyectamos los textos en el Modal
        document.getElementById('modal-id').value = id;
        document.getElementById('modal-nombre').textContent = nombre;
        document.getElementById('modal-direccion').innerHTML = `<i class="bi bi-geo-alt"></i> ${direccion}`;
        document.getElementById('modal-horario').innerHTML = `<i class="bi bi-clock"></i> ${horario}`;
        document.getElementById('modal-descripcion').textContent = descripcion;
        document.getElementById('modal-contacto').innerHTML = `
            <div><i class="bi bi-envelope"></i> ${correo}</div>
            <div><i class="bi bi-whatsapp text-success"></i> ${'+56' . whatsapp || 'Sin número'}</div>
            <div><i class="bi bi-facebook text-primary"></i> ${facebook || 'Sin Facebook'}</div>
            <div><i class="bi bi-instagram text-danger"></i> ${instagram || 'Sin Instagram'}</div>
        `;

        const galeria = document.getElementById('modal-galeria');
        galeria.innerHTML = '';
        
        if (imagenes.length === 0) {
            galeria.innerHTML = '<span class="text-muted small">Este emprendimiento no subió imágenes.</span>';
        } else {
            imagenes.forEach(img => {
                const srcReal = `/smart_city/public/uploads/${img}`; 
                
                const div = document.createElement('div');
                div.className = 'border p-1 rounded bg-white';
                div.innerHTML = `
                    <img src="${srcReal}" class="rounded" style="width:100px; height:100px; object-fit:cover;" 
                         onerror="this.onerror=null; this.src='https://placehold.co/100?text=Error+404';">
                `;
                galeria.appendChild(div);
            });
        }

        document.getElementById('radioAprobar').checked = true;
        document.getElementById('wrapper-observacion').style.display = 'none';
        document.getElementById('observacion').required = false;
        document.getElementById('observacion').value = '';
    });

    const radioAprobar = document.getElementById('radioAprobar');
    const radioRechazar = document.getElementById('radioRechazar');
    const wrapperObservacion = document.getElementById('wrapper-observacion');
    const inputObservacion = document.getElementById('observacion');

    radioAprobar.addEventListener('change', function() {
        if (this.checked) {
            wrapperObservacion.style.display = 'none';
            inputObservacion.required = false;
        }
    });

    radioRechazar.addEventListener('change', function() {
        if (this.checked) {
            wrapperObservacion.style.display = 'block';
            inputObservacion.required = true;
            inputObservacion.focus();
        }
    });
</script>
</body>
</html>