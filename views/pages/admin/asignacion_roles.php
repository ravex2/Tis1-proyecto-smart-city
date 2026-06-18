<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    require_once __DIR__ . '/../../../models/usuario.php';
    require_once __DIR__ . '/../../../models/rol.php';
    
    $usuarioModelo = new Usuario();
    $rolModelo = new Rol();
    
    $usuarios = $usuarioModelo->findAllWithRoles();
    $roles = $rolModelo->findAll();
    
    
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: ?ruta=login');
        exit;
    }   

    $usuarioLogeado = $_SESSION['user'] ?? null;
?>

<!doctype html>
<html lang="es">
    <head>
        <title>Title</title>
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
        <link rel="stylesheet" href="assets/css/panel.css">
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../../layout/sidebar.php"; ?>

                <div class="col-md-10 col-lg-10 p-4">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-1">Asignación de roles</h3>
                            <p class="text-muted mb-0">Gestión los roles de los funcionarios municipales</p>
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
                    <input type="text" id="buscar" class="form-control mb-3" placeholder="Buscar usuario...">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    <div
                        class="table-responsive">
                        <table
                            class="table table-hover table-striped align-middle shadow-sm">
                            <thead >
                                <tr>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                    <td><?= $usuario['nombre'].' '.$usuario['apellido'] ?></td>
                                    <td><?= $usuario['correo'] ?></td>
                                    <td>
                                    <?php
                                        $color = match($usuario['nombre_rol']) {
                                            'Administrador' => 'danger',
                                            'Encargado de Comunicaciones' => 'primary',
                                            'Usuario' => 'secondary',
                                            default => 'dark'
                                        };
                                        ?>
                                    <span class="badge bg-<?= $color ?>">
                                        <span class="badge bg-<?= $color ?>">
                                        <?= $usuario['nombre_rol'] ?>
                                        </span>
                                    </td>
                                    <td> <button type='button' class='btn btn-outline-primary btn-sm flex-fill' data-bs-toggle='modal' data-bs-target='#modalId' 
                                    onclick="abrirModal(<?= $usuario['rut'] ?>, <?= $usuario['id_rol'] ?>)">
                                    <i class="bi bi-pencil-square me-1"></i>Editar Rol</button> </td>
                                    </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div
                class="modal fade"
                id="modalId"
                tabindex="-1"
                data-bs-backdrop="static"
                data-bs-keyboard="false"
                
                role="dialog"
                aria-labelledby="modalTitleId"
                aria-hidden="true">
                <div
                    class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                    role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">
                                Editar Rol
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>
                        <form method="POST" action="?ruta=asignar_rol">
                            <div class="modal-body">
                                <input type="hidden" name="rut" id="rut_usuario">

                                <label>Rol:</label>
                                <select name="id_rol" id="select_rol" class="form-select" required>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= $rol['id_rol'] ?>">
                                            <?= $rol['nombre_rol'] ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        <script>
            function abrirModal(rut, idRol) {
                document.getElementById('rut_usuario').value = rut;
                document.getElementById('select_rol').value = idRol;

                const modal = new bootstrap.Modal(document.getElementById('modalId'));
                modal.show();
            }
        </script>
        <script>
            document.getElementById('buscar').addEventListener('keyup', function () {
                let value = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
                });
            });
        </script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
