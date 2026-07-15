<?php
require_once __DIR__ . "/../../../config/database.php";
$db = getDatabase();

$sectores = $db->query("SELECT id_sector, nombre FROM sector");
$rubros = $db->query("SELECT id_rubro, nombre_rubro FROM rubro");

$queryParams = [];
if (!empty($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $queryParams);
}

$buscar = trim($queryParams['buscar'] ?? $_GET['buscar'] ?? '');
$filtro_sector = $queryParams['sector'] ?? $_GET['sector'] ?? '';
$filtro_rubro = $queryParams['rubro'] ?? $_GET['rubro'] ?? '';
$isAjax = isset($queryParams['ajax']) || isset($_GET['ajax']);

$consulta = "SELECT n.*, s.nombre AS nombre_sector, r.nombre_rubro 
             FROM negocio_local n
             INNER JOIN sector s ON n.id_sector = s.id_sector
             INNER JOIN rubro r ON n.id_rubro = r.id_rubro
             WHERE 1=1 AND n.tipo_estado = 'aprobado'";
$parametros = [];

if (!empty($buscar)) {
    $consulta .= " AND (n.nombre LIKE ? OR n.descripcion LIKE ? OR n.direccion LIKE ?)";
    array_push($parametros, "%$buscar%", "%$buscar%", "%$buscar%");
}
if (!empty($filtro_sector)) { $consulta .= " AND n.id_sector = ?"; $parametros[] = $filtro_sector; }
if (!empty($filtro_rubro))  { $consulta .= " AND n.id_rubro = ?";  $parametros[] = $filtro_rubro; }

$consulta .= " ORDER BY n.nombre ASC";
$emprendimientos = $db->query($consulta, $parametros);

// Procesar emprendimientos mapeando sus imágenes correspondientes
$lista_emprendimientos = [];
foreach ($emprendimientos as $biz) {
    $imgData = $db->query("SELECT ruta_imagen FROM imagenes_negocios WHERE id_negocio = ?", [$biz['id_negocio']]);
    $biz['imagenes'] = array_column($imgData, 'ruta_imagen');
    $lista_emprendimientos[] = $biz;
}

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($lista_emprendimientos, JSON_UNESCAPED_UNICODE);
    exit; 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity - Emprendimientos Locales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light" style="font-family: sans-serif;">

<?php include __DIR__ . "/../../../views/layout/navbar_user.php"; ?>

<div class="container" style="margin-top: 90px; max-width: 1100px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Comercio Local</h2>
            <p class="text-muted mb-0">Descubre y apoya los negocios de tu comunidad</p>
        </div>
        <a href="?ruta=registrar_emprendimiento" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>Registrar el mío
        </a>
    </div>

    <div class="card border-0 rounded-4 p-4 shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-muted">Buscar por palabra clave</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="filtro-buscar" class="form-control bg-light border-start-0" placeholder="Ej: Café..." value="<?= htmlspecialchars($buscar) ?>">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Sector</label>
                <select id="filtro-sector" class="form-select bg-light">
                    <option value="">Todos los sectores</option>
                    <?php foreach ($sectores as $s): ?>
                        <option value="<?= $s['id_sector'] ?>" <?= $filtro_sector == $s['id_sector'] ? 'selected' : '' ?>><?= htmlspecialchars($s['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Rubro</label>
                <select id="filtro-rubro" class="form-select bg-light">
                    <option value="">Todos los rubros</option>
                    <?php foreach ($rubros as $rubro): ?> 
                        <option value="<?= $rubro['id_rubro'] ?>" <?= $filtro_rubro == $rubro['id_rubro'] ? 'selected' : '' ?>><?= htmlspecialchars($rubro['nombre_rubro']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-1">
                <button type="button" id="btn-limpiar" class="btn btn-light w-100 rounded-circle" title="Limpiar Filtros" style="display: <?= ($buscar || $filtro_sector || $filtro_rubro) ? 'block' : 'none' ?>;">
                    <i class="bi bi-x-circle-fill text-secondary"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5" id="contenedor-negocios">
        <?php if (count($lista_emprendimientos) > 0): ?>
            <?php foreach ($lista_emprendimientos as $biz): 
                $foto = $biz['imagenes'][0] ?? null;
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100" style="cursor: pointer;"
                         data-bs-toggle="modal" data-bs-target="#modalDetalleNegocio"
                         data-biz='<?= json_encode($biz, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'>
                         
                        <div style="height: 160px; background-color: #eaeaea; position: relative;" class="rounded-top-4 overflow-hidden">
                            <span class="badge position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm bg-white text-primary fw-semibold">
                                <?= htmlspecialchars($biz['nombre_rubro']) ?>
                            </span>
                            <?php if ($foto): ?>
                                <img src="/Tis1-proyecto-smart-city/public/uploads/<?= $foto ?>" class="w-100 h-100 object-fit-cover">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-shop fs-1 opacity-50"></i></div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($biz['nombre']) ?></h5>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($biz['direccion']) ?></p>
                            <p class="text-secondary small flex-grow-1 text-truncate"><?= htmlspecialchars($biz['descripcion'] ?? 'Sin descripción.') ?></p>
                            
                            <hr class="opacity-10 my-3">
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="text-muted small"><i class="bi bi-clock me-1"></i><?= substr($biz['hora_apertura'], 0, 5) ?> - <?= substr($biz['hora_cierre'], 0, 5) ?></span>
                                <div class="d-flex gap-2" style="position: relative; z-index: 5;">
                                    <?php if(!empty($biz['whatsapp'])): ?>
                                        <a href="https://wa.me/56<?= $biz['whatsapp'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-circle" onclick="event.stopPropagation();"><i class="bi bi-whatsapp"></i></a>
                                    <?php endif; ?>
                                    <?php if(!empty($biz['instagram'])): ?>
                                        <a href="https://instagram.com/<?= $biz['instagram'] ?>" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle" onclick="event.stopPropagation();"><i class="bi bi-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if(!empty($biz['facebook'])): ?>
                                        <a href="https://facebook.com/<?= $biz['facebook'] ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" onclick="event.stopPropagation();"><i class="bi bi-facebook"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-shop-window fs-1 text-muted opacity-50 d-block mb-2"></i>
                <h5 class="fw-bold text-secondary">No se encontraron negocios</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalDetalleNegocio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 bg-light py-3">
                <div class="d-flex gap-2">
                    <span class="badge bg-white text-primary border px-3 py-2 rounded-pill shadow-sm" id="view-rubro"></span>
                    <span class="badge bg-secondary px-3 py-2 rounded-pill shadow-sm" id="view-sector"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-7">
                        <h3 class="fw-bold text-dark mb-1" id="view-nombre"></h3>
                        <p class="text-muted small mb-3" id="view-direccion"></p>
                        <label class="fw-bold text-muted small uppercase">Sobre el Negocio</label>
                        <p class="text-secondary small bg-light p-3 rounded-3 mb-4" id="view-descripcion" style="white-space: pre-line;"></p>
                        <div class="text-dark small"><i class="bi bi-clock-history text-primary fs-5 me-2"></i><span id="view-horario"></span></div>
                    </div>
                    <div class="col-md-5 border-start ps-md-4">
                        <label class="fw-bold text-muted small d-block mb-3">Canales de Contacto</label>
                        <div class="d-grid gap-2 mb-4" id="view-contacto-container"></div>
                        <label class="fw-bold text-muted small d-block mb-2">Galería</label>
                        <div id="view-galeria" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"
></script>

<script>
    const inputBuscar = document.getElementById('filtro-buscar');
    const selectSector = document.getElementById('filtro-sector');
    const selectRubro = document.getElementById('filtro-rubro');
    const btnLimpiar = document.getElementById('btn-limpiar');
    const contenedor = document.getElementById('contenedor-negocios');

    inputBuscar.addEventListener('input', () => {
        console.log("Escribiendo búsqueda");
        ejecutarAjax();
    });

    selectSector.addEventListener('change', () => {
        console.log("Cambio sector");
        ejecutarAjax();
    });

    selectRubro.addEventListener('change', () => {
        console.log("Cambio rubro");
        ejecutarAjax();
    });
    
    btnLimpiar.addEventListener('click', () => {
        inputBuscar.value = ''; selectSector.value = ''; selectRubro.value = '';
        ejecutarAjax();
    });

    async function ejecutarAjax() {

        console.log("ejecutarAjax");

        btnLimpiar.style.display =
            (inputBuscar.value || selectSector.value || selectRubro.value)
                ? 'block'
                : 'none';

        const parametros = new URLSearchParams();

        parametros.append('ruta', 'comercio');
        parametros.append('ajax', '1');
        parametros.append('buscar', inputBuscar.value);
        parametros.append('sector', selectSector.value);
        parametros.append('rubro', selectRubro.value);

        const url = window.location.pathname + '?' + parametros.toString();

        console.log("URL AJAX:", url);

        try {

            const response = await fetch(url);

            const texto = await response.text();

            console.log("Respuesta servidor:", texto);

            const negocios = JSON.parse(texto);

            renderizarHTML(negocios);

        } catch (error) {

            console.error("Error AJAX:", error);

        }
    }

    function renderizarHTML(lista) {
        contenedor.innerHTML = '';

        if (lista.length === 0) {
            contenedor.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-shop-window fs-1 text-muted opacity-50 d-block mb-2"></i>
                    <h5 class="fw-bold text-secondary">No se encontraron negocios</h5>
                </div>`;
            return;
        }

        lista.forEach(biz => {
            const foto = biz.imagenes[0] || null;
            const imgHTML = foto 
                ? `<img src="/smart_city/public/uploads/${foto}" class="w-100 h-100 object-fit-cover">`
                : `<div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-shop fs-1 opacity-50"></i></div>`;

            const stringifiedData = JSON.stringify(biz).replace(/'/g, "&#39;");

            contenedor.innerHTML += `
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100" style="cursor: pointer;"
                         data-bs-toggle="modal" data-bs-target="#modalDetalleNegocio" data-biz='${stringifiedData}'>
                        <div style="height: 160px; background-color: #eaeaea; position: relative;" class="rounded-top-4 overflow-hidden">
                            <span class="badge position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill shadow-sm bg-white text-primary fw-semibold">${biz.nombre_rubro}</span>
                            ${imgHTML}
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="fw-bold text-dark mb-1">${biz.nombre}</h5>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${biz.direccion}</p>
                            <p class="text-secondary small flex-grow-1 text-truncate">${biz.descripcion || 'Sin descripción.'}</p>
                            <hr class="opacity-10 my-3">
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="text-muted small"><i class="bi bi-clock me-1"></i>${biz.hora_apertura.substring(0, 5)} - ${biz.hora_cierre.substring(0, 5)}</span>
                                <div class="d-flex gap-2" style="position: relative; z-index: 5;">
                                    ${biz.whatsapp ? `<a href="https://wa.me/56${biz.whatsapp}" target="_blank" class="btn btn-sm btn-outline-success rounded-circle" onclick="event.stopPropagation();"><i class="bi bi-whatsapp"></i></a>` : ''}
                                    ${biz.instagram ? `<a href="https://instagram.com/${biz.instagram}" target="_blank" class="btn btn-sm btn-outline-danger rounded-circle" onclick="event.stopPropagation();"><i class="bi bi-instagram"></i></a>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
    }

    document.getElementById('modalDetalleNegocio').addEventListener('show.bs.modal', function (event) {
        const data = JSON.parse(event.relatedTarget.getAttribute('data-biz'));

        document.getElementById('view-nombre').textContent = data.nombre;
        document.getElementById('view-rubro').textContent = data.nombre_rubro;
        document.getElementById('view-sector').textContent = data.nombre_sector;
        document.getElementById('view-direccion').innerHTML = `<i class="bi bi-geo-alt-fill text-danger me-1"></i>${data.direccion}`;
        document.getElementById('view-descripcion').textContent = data.descripcion || 'Sin descripción.';
        document.getElementById('view-horario').innerHTML = `<strong>Horarios:</strong> ${data.dias_abierto || 'Lunes a Viernes'} de ${data.hora_apertura.substring(0,5)} a ${data.hora_cierre.substring(0,5)}`;

        const container = document.getElementById('view-contacto-container');
        container.innerHTML = '';
        if(data.whatsapp) container.innerHTML += `<a href="https://wa.me/56${data.whatsapp}" target="_blank" class="btn btn-outline-success rounded-pill text-start p-2 small"><i class="bi bi-whatsapp me-2"></i> WhatsApp</a>`;
        if(data.instagram) container.innerHTML += `<a href="https://instagram.com/${data.instagram}" target="_blank" class="btn btn-outline-danger rounded-pill text-start p-2 small"><i class="bi bi-instagram me-2"></i> Instagram</a>`;
        if(data.facebook) container.innerHTML += `<a href="https://facebook.com/${data.facebook}" target="_blank" class="btn btn-outline-primary rounded-pill text-start p-2 small"><i class="bi bi-facebook me-2"></i> Facebook</a>`;
        if(data.correo_electronico) container.innerHTML += `<a href="mailto:${data.correo_electronico}" class="btn btn-outline-secondary rounded-pill text-start p-2 small"><i class="bi bi-envelope me-2"></i> Enviar Correo</a>`;

        const galeria = document.getElementById('view-galeria');
        galeria.innerHTML = data.imagenes.length ? '' : '<span class="text-muted small">Sin imágenes adicionales.</span>';
        data.imagenes.forEach(img => {
            galeria.innerHTML += `
                <div class="border p-1 rounded bg-white shadow-sm">
                    <a href="/Tis1-proyecto-smart-city/public/uploads/${img}" target="_blank">
                        <img src="/Tis1-proyecto-smart-city/public/uploads/${img}" class="rounded" style="width:70px; height:70px; object-fit:cover;">
                    </a>
                </div>`;
        });
    });
    console.log("JavaScript cargado");
</script>
</body>
</html>