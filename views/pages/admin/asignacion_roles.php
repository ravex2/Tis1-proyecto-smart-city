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
                    <div
                        class="table-responsive">
                        <table
                            class="table">
                            <thead>
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
                                    <td><?= $usuario['nombre_rol'] ?></td>
                                    <td> <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalId' onclick="abrirModal(<?= $usuario['rut'] ?>, <?= $usuario['id_rol'] ?>)">Editar Rol</button> </td>
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

                const modal = new bootstrap.Modal(document.getElementById('modalRol'));
                modal.show();
            }
        </script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
