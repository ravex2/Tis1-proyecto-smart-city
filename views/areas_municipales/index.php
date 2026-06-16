<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    
    require_once __DIR__ . '/../../models/Area.php';
    require_once __DIR__ . '/../../config/database.php';

    // LISTA DE ÁREAS
    $areas = listarAreas();

    // LISTA DE MUNICIPALIDADES
    $db = getDatabase();
    $municipalidades = $db->query("SELECT id_municipalidad, nombre FROM municipalidad");

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
            crossorigin="anonymous"
        />
    </head>
    <body>
        <div class="container">
            <h3>Áreas Municipales</h3>
            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalId"
                onclick="crearArea()">
                Nueva Área municipal
            </button>

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
                                <form id="formArea" action="ingresar.php" method="POST">
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
            
            <div
                class="container table-responsive"
            >
                <table
                    class="table"
                >
                    <thead>
                        <tr>
                            <th scope="col">ID Área</th>
                            <th scope="col">Nombre Área</th>
                            <th scope="col">Descripción Área</th>
                            <th scope="col">Municipalidad</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php foreach ($areas as $row) { ?>
                        <tr>
                            <td><?= $row['id_area'] ?></td>
                            <td><?= $row['nombre_area'] ?></td>
                            <td><?= $row['descripcion'] ?></td>
                            <td><?= $row['nombre_municipalidad'] ?></td>

                            <td>
                                <button
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalId"
                                    onclick="editarArea(
                                        <?= $row['id_area'] ?>,
                                        '<?= htmlspecialchars($row['nombre_area'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($row['descripcion'], ENT_QUOTES) ?>',
                                        <?= $row['id_municipalidad'] ?>
                                    )">
                                    Editar
                                </button>

                                <a class="btn btn-danger"
                                href="delete.php?id_enviado=<?= $row['id_area'] ?>">
                                Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
            

        </div>
        
        <script>

            function crearArea() {

                document.getElementById('tituloModal').innerText = 'Agregar Área Municipal';

                document.getElementById('formArea').action = 'ingresar.php';

                document.getElementById('id_area').value = '';

                document.getElementById('nombre').value = '';

                document.getElementById('descripcion').value = '';

                document.getElementById('id_municipalidad').selectedIndex = 0;

                document.getElementById('formBtn').innerText = 'Ingresar';
            }

            function editarArea(id, nombre, descripcion, idMunicipalidad) {

                document.getElementById('tituloModal').innerText = 'Editar Área Municipal';

                document.getElementById('formArea').action = 'editar.php';

                document.getElementById('id_area').value = id;

                document.getElementById('nombre').value = nombre;

                document.getElementById('descripcion').value = descripcion;

                document.getElementById('id_municipalidad').value = idMunicipalidad;

                document.getElementById('formBtn').innerText = 'Actualizar';
            }

        </script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
