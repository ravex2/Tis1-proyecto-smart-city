<?php

use Proyecto\core\Database;

    require_once __DIR__ . '/../../../config/database.php';

    $db = getDatabase();

    $sectores = $db->query("SELECT id_sector, nombre FROM sector");
    $rubros = $db->query("SELECT id_rubro, nombre_rubro FROM rubro");

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
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <?php include __DIR__ . "/../../layout/navbar_user.php"; ?>
        <div class="container" style="margin-top: 75px;">
            <h2 class="fw-bold mb-1">Registra tu emprendimiento</h2>
            <p class="text-muted mb-0"> Da a conocer tu emprendimiento a la comunidad</p>

            <form class="border shadow-sm rounded bg-light p-4" action="?ruta=ingresar_emprendimiento" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"> Nombre Negocio</label>
                        <input type="text" name="nombre_negocio" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> Rubro</label>
                        <select name="id_rubro" class="form-select" required>
                            <option value="" disabled selected>Seleccione un rubro...</option>
                            <?php foreach ($rubros as $rubro): ?>
                                <option value="<?= htmlspecialchars((string)$rubro['id_rubro']) ?>">
                                    <?= htmlspecialchars($rubro['nombre_rubro']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> Sector</label>
                        <select name="id_sector" class="form-select" required>
                            <option value="" disabled selected>Seleccione un sector...</option>
                            <?php foreach ($sectores as $sector): ?>
                                <option value="<?= htmlspecialchars((string)$sector['id_sector']) ?>">
                                    <?= htmlspecialchars($sector['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label"> Dirección</label>
                        <input type="text" class="form-control" name="direccion" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"> Correo Electrónico</label>
                        <input type="email" class="form-control" name="correo_electronico" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Días de Atención</label>
                        <select name="dias_abierto" class="form-select" required>
                            <option value="" disabled selected>Seleccione los días...</option>
                            <option value="Lunes a Viernes">Lunes a Viernes</option>
                            <option value="Lunes a Sábado">Lunes a Sábado</option>
                            <option value="Lunes a Domingo">Lunes a Domingo</option>
                            <option value="Fines de Semana">Fines de Semana (Sáb-Dom)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hora Apertura</label>
                        <input type="time" name="hora_apertura" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hora Cierre</label>
                        <input type="time" name="hora_cierre" class="form-control" required>
                    </div>
                </div>
                <div class="row mt-4">
                    <h5 class="fw-bold mb-3">Redes Sociales y Contacto</h5>

                    <div class="col-md-4">
                        <label class="form-label">Instagram</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">@</span>
                            <input type="text" name="instagram" class="form-control" placeholder="nombre_usuario">
                        </div>
                        <div class="form-text text-muted">Solo el nombre de usuario.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Facebook</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">fb.com/</span>
                            <input type="text" name="facebook" class="form-control" placeholder="pagina_negocio">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">WhatsApp de Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">+56</span>
                            <input type="tel" name="whatsapp" class="form-control" placeholder="912345678" maxlength="9">
                        </div>
                        <div class="form-text text-muted">9 dígitos, sin el +56.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <label class="form-label">Descripción del Emprendimiento</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Cuéntale a la comunidad de qué trata tu negocio, qué productos vendes o qué servicios ofreces..."></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Imagenes o Logo del Negocio</label>
                        <div class="border rounded p-3 bg-white text-center d-flex flex-column justify-content-center" style="min-height: 115px;">
                            <input type="file" name="imagenes[]" class="form-control" accept="image/*" multiple>
                            <div class="form-text text-muted small">Formatos permitidos: JPG, PNG. Máximo 2MB.</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"> Ingresar </button>
                    </div>
                </div>
            </form>
            

        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
