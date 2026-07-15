<?php
    require_once __DIR__ . '/../../../config/database.php';
    $db = getDatabase();

    $sectores = $db->query("SELECT id_sector, nombre FROM sector");
    $rubros = $db->query("SELECT id_rubro, nombre_rubro FROM rubro");
?>

<!doctype html>
<html lang="es">
    <head>
        <title>Registra tu Emprendimiento - SmartCity</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    </head>

    <body class="bg-light" style="font-family: sans-serif;">
        
        <?php include __DIR__ . "/../../layout/navbar_user.php"; ?>
        
        <div class="container" style="margin-top: 75px; max-width: 850px; margin-bottom: 80px;">
            
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark mt-2 mb-1">Registra tu Emprendimiento</h2>
                <p class="text-muted">Da a conocer tu negocio a todos los vecinos de la comunidad</p>
            </div>

            <form id="formEmprendimiento" class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white" action="?ruta=ingresar_emprendimiento" method="POST" enctype="multipart/form-data">
                
                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-info-circle me-2"></i>Información General</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold small text-muted">Nombre del Negocio</label>
                        <input type="text" name="nombre_negocio" class="form-control bg-light border-0 py-2" placeholder="Ej: Café Central" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Rubro / Categoría</label>
                        <select name="id_rubro" class="form-select bg-light border-0 py-2" required>
                            <option value="" disabled selected>Seleccione un rubro...</option>
                            <?php foreach ($rubros as $rubro): ?>
                                <option value="<?= htmlspecialchars((string)$rubro['id_rubro']) ?>">
                                    <?= htmlspecialchars($rubro['nombre_rubro']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Sector / Zona</label>
                        <select name="id_sector" class="form-select bg-light border-0 py-2" required>
                            <option value="" disabled selected>Seleccione un sector...</option>
                            <?php foreach ($sectores as $sector): ?>
                                <option value="<?= htmlspecialchars((string)$sector['id_sector']) ?>">
                                    <?= htmlspecialchars($sector['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Dirección</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control bg-light border-0 py-2" name="direccion" placeholder="Calle, Número, Local" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-muted">Correo Electrónico de Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control bg-light border-0 py-2" name="correo_electronico" placeholder="contacto@minegocio.com" required>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-clock me-2"></i>Horarios de Atención</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Días de Apertura</label>
                        <select name="dias_abierto" class="form-select bg-light border-0 py-2" required>
                            <option value="" disabled selected>Seleccione días...</option>
                            <option value="Lunes a Viernes">Lunes a Viernes</option>
                            <option value="Lunes a Sábado">Lunes a Sábado</option>
                            <option value="Lunes a Domingo">Lunes a Domingo</option>
                            <option value="Fines de Semana">Fines de Semana (Sáb-Dom)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Hora Apertura</label>
                        <input id="hora_apertura" type="time" name="hora_apertura" class="form-control bg-light border-0 py-2" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Hora Cierre</label>
                        <input id="hora_cierre" type="time" name="hora_cierre" class="form-control bg-light border-0 py-2" required>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-share me-2"></i>Redes Sociales y WhatsApp</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Instagram</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 text-danger bg-danger-subtle"><i class="bi bi-instagram"></i></span>
                            <input type="text" name="instagram" class="form-control bg-light border-0 py-2" placeholder="nombre_usuario">
                        </div>
                        <div class="form-text small text-muted">Sin el símbolo @</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Facebook</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 text-primary bg-primary-subtle"><i class="bi bi-facebook"></i></span>
                            <input type="text" name="facebook" class="form-control bg-light border-0 py-2" placeholder="pagina_negocio">
                        </div>
                        <div class="form-text small text-muted">Solo el nombre de la página</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">WhatsApp Comercial</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 text-success bg-success-subtle fw-bold">+56</span>
                            <input type="tel" name="whatsapp" class="form-control bg-light border-0 py-2" placeholder="912345678" maxlength="9">
                        </div>
                        <div class="form-text small text-muted">9 dígitos, sin espacios.</div>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                <div class="row g-4 mb-4">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold small text-muted">Descripción del Emprendimiento</label>
                        <textarea name="descripcion" class="form-control bg-light border-0" rows="4" placeholder="Cuéntale a tus vecinos qué productos vendes, especialidades o servicios que ofreces..." style="resize: none;"></textarea>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold small text-muted">Imágenes o Logo</label>
                        <div class="card border-0 p-3 bg-light h-100 justify-content-center text-center rounded-3">
                            <i class="bi bi-images fs-3 text-muted mb-2"></i>
                            <input type="file" name="imagenes[]" class="form-control form-control-sm border-0 shadow-sm" accept="image/*" multiple>
                            <div class="form-text text-muted small mt-2">Formatos: JPG, PNG. <br>Máximo 2MB por imagen.</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3 fs-6">
                        <i class="bi bi-check-circle-fill me-2"></i>Enviar Solicitud de Registro
                    </button>
                </div>
            </form>
        </div>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
        <script>
            document.getElementById("formEmprendimiento").addEventListener("submit", function(e) {

                const apertura = document.getElementById("hora_apertura").value;
                const cierre = document.getElementById("hora_cierre").value;

                if (apertura >= cierre) {
                    e.preventDefault();
                    alert("La hora de apertura debe ser menor que la hora de cierre.");
                }

            });
        </script>
    </body>
</html>