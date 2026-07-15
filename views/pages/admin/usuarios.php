<?php
require_once __DIR__ . '../../../../controllers/usuario.controlador.php';


// esto debe moverse al controlador interno

$controlador = new UsuarioController();
$usuarios = $controlador->obtenerUsuarios();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_crear'])) {
    try {
        $datos = [
            'rut'         => $_POST['rut'],
            'nombre'      => $_POST['nombre'],
            'apellido'    => $_POST['apellido'],
            'correo'      => $_POST['correo'],
            'direccion'   => $_POST['direccion'],
            'contrasenha' =>  $_POST['contrasenha'], //password_hash($_POST['contrasenha'], PASSWORD_DEFAULT),
            'id_rol'      => $_POST['id_rol'],
            'id_sector'   => $_POST['id_sector']
        ];

        $resultado = $controlador->crearUsuario($datos);
        
        if ($resultado) {
            echo "<script>alert('Usuario creado con éxito'); window.location='usuario.php';</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error al crear usuario: " . $e->getMessage() . "');</script>";
    }
}

// ELIMINAR
if (isset($_POST['btn_eliminar'])) {
    $controlador->eliminarUsuario($_POST['rut_eliminar']);
    header("Location: usuario.php?status=deleted");
}

// EDITAR 
if (isset($_POST['btn_editar'])) {
    $rut = $_POST['rut'];
    echo $_POST["id_rol"];
    $datos = [
        'rut'         => $_POST['rut'],
        'nombre'      => $_POST['nombre'],
        'apellido'    => $_POST['apellido'],
        'correo'      => $_POST['correo'],
        'direccion'   => $_POST['direccion'],
        'contrasenha' => $_POST['contrasenha'],
        'id_rol'      => $_POST['id_rol'],
        'id_sector'   => $_POST['id_sector']
    ];
    $controlador->editarUsuario($rut, $datos);
    //header("Location: usuario.php?status=updated");
}
?>

<? include __DIR__ . "../../../layout/header.php"; ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
         <?php include __DIR__ . "../../../layout/sidebar.php"; ?>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-5 bg-light-soft">
            <!-- Header -->
            <header class="d-flex justify-content-end align-items-center py-4">
                <?php include __DIR__ . "../../../layout/panel/navbar_user_panel.php"; ?>
            </header>

            <!-- Título y Acciones -->
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Configuración de Estructura</h2>
                    <p class="text-muted small mb-0">Administra quiénes operan la plataforma y sus áreas.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary rounded-pill px-4 btn-sm shadow-primary" data-bs-toggle="modal" data-bs-target="#postModalUsuario"><i class="bi bi-person-plus-fill me-2"></i> Crear Usuario</button>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-12">
                    <div class="card border-0 shadow-card rounded-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0">Lista de Funcionarios</h6>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaUsuarios" class="table table-hover align-middle border-0">
                                <thead class="table-light border-0">
                                    <tr class="text-muted small">
                                        <th class="border-0 fw-bold py-3">USUARIO</th>
                                        <th class="border-0 fw-bold py-3">INFORMACION DE USUARIO</th>
                                        <th class="border-0 fw-bold py-3">ROL</th>
                                        <th class="border-0 fw-bold py-3 text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td class="border-0 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="https://i.pravatar.cc/150?u=12" class="user-avatar-list me-3">
                                                <div>
                                                    <p class="fw-bold mb-0 small"><?=$u['rut']?></p>
                                                    <p class="text-muted tiny mb-0"><?=$u['correo']?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-0"><?= $u['nombre'] . ' ' . $u['apellido'] ?></td>
                                        <td class="border-0"><span class="text-primary fw-bold tiny">Supervisor</span></td>
                                        <td class="border-0">
                                            <button class="btn-action me-1" data-bs-toggle="modal" data-bs-target="#postModalUsuarioEditar" 
                                                onclick="prepararEdicion('<?= $u['rut'] ?>', '<?= $u['nombre'] ?>', '<?= $u['apellido'] ?>','<?=$u['contrasenha'] ?>' ,'<?= $u['correo'] ?>', '<?= $u['direccion'] ?>', '<?= $u['id_rol'] ?>', '<?= $u['id_sector'] ?>')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn-action text-danger" data-bs-toggle="modal" data-bs-target="#postModalUsuarioEliminar" 
                                                onclick="document.getElementById('rut_eliminar').value = '<?= $u['rut'] ?>'">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal crear usuarios -->
<div class="modal fade" id="postModalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Crear un usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="?ruta=usuarios" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label tiny fw-bold">Rut</label>
                        <input type="text" name="rut" class="form-control" placeholder="Ej: 0000000-k" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Apellido</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Correo</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Direccion</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Contraseña</label>
                        <input type="password" name="contrasenha" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold">Rol</label>
                        <select name="id_rol" class="form-select border-0 bg-light">
                            <option value="1">Administrador</option>
                            <option value="2">E.municipal</option>
                            <option value="3">Visitante</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold">Sector</label>
                        <select name="id_sector" class="form-select border-0 bg-light">
                            <option value="1">Concepcion</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" name="btn_crear" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            Crear usuario
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<!-- Modal editar usuarios -->
<div class="modal fade" id="postModalUsuarioEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Crear un usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="?ruta=usuarios" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label tiny fw-bold">Rut</label>
                        <input type="text" name="rut" id="editar_rut" class="form-control" placeholder="Ej: 0000000-k" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Nombre</label>
                        <input type="text" name="nombre" id="editar_nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Apellido</label>
                        <input type="text" name="apellido" id="editar_apellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Correo</label>
                        <input type="email" name="correo" id="editar_correo" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">contraseña</label>
                        <input type="text" name="contrasenha" id="editar_contrasenha" class="form-control" required>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label tiny fw-bold">Direccion</label>
                        <input type="text" name="direccion" id="editar_direccion" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold">Rol</label>
                        <select name="id_rol" id="editar_id_rol" class="form-select border-0 bg-light">
                            <option value="1">Administrador</option>
                            <option value="2">E.municipal</option>
                            <option value="3">Visitante</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label tiny fw-bold">Sector</label>
                        <select name="id_sector" id="editar_id_sector" class="form-select border-0 bg-light">
                            <option value="1">Concepcion</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" name="btn_editar" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            Editar usuario
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<!-- Modal eliminar -->
<div class="modal fade" id="postModalUsuarioEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <form method="POST">
                <input type="hidden" name="rut_eliminar" id="rut_eliminar">
                <h5>¿Seguro que deseas eliminar este usuario?</h5>
                <button type="submit" name="btn_eliminar" class="btn btn-danger w-100">Confirmar</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . "../../../layout/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function prepararEdicion(rut, nombre, apellido,contrasenha, correo, direccion, rol,sector) {
        // Llenar los inputs del modal de edición
        document.getElementById('editar_rut').value = rut;
        document.getElementById('editar_rut').readOnly = true; // El RUT no se debe editar
        document.getElementById('editar_nombre').value = nombre;
        document.getElementById('editar_apellido').value = apellido;
        document.getElementById('editar_correo').value = correo;
        document.getElementById('editar_direccion').value = direccion;
        document.getElementById('editar_id_rol').value = rol;
        document.getElementById('editar_id_sector').value = sector;
        document.getElementById('editar_contrasenha').value = contrasenha;

    }

    $(document).ready(function() {
        // Diagnóstico: Esto debe imprimir "function" en la consola. Si imprime "undefined", hay un conflicto.
        console.log("¿DataTables cargó correctamente?:", typeof $.fn.DataTable);

        if (typeof $.fn.DataTable === 'function') {
            // Destruir si ya existe para evitar errores de recarga
            if ($.fn.DataTable.isDataTable('#sectorTabla')) {
                $('#tablaUsuarios').DataTable().clear().destroy();
            }

            $('#tablaUsuarios').DataTable({
                language: { 
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' 
                },
                pageLength: 10,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: 3 } // No ordenar la columna de acciones
                ]
            });
            console.log("✅ Tabla inicializada con éxito.");
        } else {
            console.error("jQuery está sobrescribiendo a DataTables. Busca otro script de jQuery en tu proyecto.");
        }
    });

</script>



</body>
</html>