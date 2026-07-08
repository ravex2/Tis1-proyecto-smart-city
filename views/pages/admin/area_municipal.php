<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    
    require_once __DIR__ . '/../../../models/Area.php';
    require_once __DIR__ . '/../../../config/database.php';

    // LISTA DE ÁREAS
    $areas = listarConFuncionarios();

    // LISTA DE MUNICIPALIDADES
    $db = getDatabase();
    $municipalidades = $db->query("SELECT id_municipalidad, nombre FROM municipalidad");

    session_start();

    $usuarioLogeado = $_SESSION['user'] ?? null;

?>
<!doctype html>
<html lang="es">
    <head>
        <title>Areas Municipales - Smart City</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"/>
        <link rel="stylesheet" href="assets/css/roles.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../../layout/sidebar.php"; ?>

                <div class="col-md-10 col-lg-10 p-4">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">Gestión de Departamentos</h3>
                            <small class="text-muted">Administración de áreas municipales</small>
                        </div>

                        <div class="d-flex align-items-center gap-2">

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalId"
                                onclick="crearArea()">
                                <i class="bi bi-plus-lg me-1"></i>
                                Nueva Área
                            </button>
                        
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
                    <div class="row">
                        <?php foreach ($areas as $row) { ?>
                            <div class="col-md-4 mb-3">
                                <div class="card shadow-sm h-100 border-0 rounded-4 hover-shadow">

                                    <div class="card-body">
                                        
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">
                                                <i class="bi bi-building text-primary me-2"></i>
                                                <?= htmlspecialchars($row['nombre_area']) ?>
                                            </h5>

                                            <span class="badge bg-primary-subtle text-primary">
                                                ID #<?= $row['id_area'] ?>
                                            </span>
                                        </div>

                                        <p class="card-text small text-muted mb-2">
                                            <?= htmlspecialchars($row['descripcion']) ?>
                                        </p>

                                        <div class="border-top pt-2 small">
                                            <div class="mb-1">
                                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                <strong>Municipalidad:</strong>
                                                <?= htmlspecialchars($row['nombre_municipalidad']) ?>
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-people-fill me-1"></i>
                                                    <?= $row['total_funcionarios'] ?> funcionarios
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-footer bg-white border-0 d-flex gap-2">
                                        
                                        <button class="btn btn-outline-primary btn-sm flex-fill"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalId"
                                            onclick="editarArea(
                                                <?= $row['id_area'] ?>,
                                                '<?= htmlspecialchars($row['nombre_area'], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($row['descripcion'], ENT_QUOTES) ?>',
                                                '<?= htmlspecialchars($row['nombre_municipalidad'], ENT_QUOTES) ?>'
                                            )">
                                            <i class="bi bi-pencil-square me-1"></i> Editar
                                        </button>

                                        <a class="btn btn-outline-danger btn-sm flex-fill"
                                            href="?ruta=eliminar_area&id_area=<?= $row['id_area'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </a>

                                    </div>

                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
            
            

        </div>
        <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">
                                Agregar Área Municipal
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form id="formArea" action="?ruta=ingresar_area" method="POST">
                                <input type="hidden" name="id_area" id="id_area">
                                <label class="form-label">Nombre área</label>
                                <input class="form-control" type="text" name="nombre" id="nombre" required>
                                <label class="form-label">Descripcion área</label>
                                <input class="form-control" type="text" name="descripcion" id="descripcion" required>
                                <label class="form-label">Municipalidad Correspondiente</label>

                                <select name="id_municipalidad" class="form-control" id="id_municipalidad" required>
                                    <option value="" disabled selected>Seleccionar Municipalidad</option>

                                    <?php 
                                        foreach ($municipalidades as $m) { ?>
                                        <option value="<?= $m['id_municipalidad'] ?>">
                                            <?= $m['nombre'] ?>
                                        </option>
                                    <?php } 
                                    ?>
                                </select>
                                <div class="pt-3">
                                    <button id="formBtn" class="btn btn-primary" type="submit">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
        <script>

            function crearArea() {

                document.getElementById('tituloModal').innerText = 'Agregar Área Municipal';

                document.getElementById('formArea').action = '?ruta=ingresar_area';

                document.getElementById('id_area').value = '';

                document.getElementById('nombre').value = '';

                document.getElementById('descripcion').value = '';

                document.getElementById('id_municipalidad').selectedIndex = 0;

                document.getElementById('formBtn').innerText = 'Ingresar';
            }

            function editarArea(id, nombre, descripcion, idMunicipalidad) {

                document.getElementById('tituloModal').innerText = 'Editar Área Municipal';

                document.getElementById('formArea').action = '?ruta=editar_area';

                document.getElementById('id_area').value = id;

                document.getElementById('nombre').value = nombre;

                document.getElementById('descripcion').value = descripcion;

                document.getElementById('id_municipalidad').value = idMunicipalidad;

                document.getElementById('formBtn').innerText = 'Actualizar';
            }

        </script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
