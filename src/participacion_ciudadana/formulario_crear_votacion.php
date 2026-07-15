<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();
$usuarioLogeado = $_SESSION['user'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $pregunta = $_POST["pregunta"];
    $descripcion = $_POST["descripcion"];
    $tipo_consulta = $_POST["tipo_consulta"];
    $fecha_termino = $_POST["fecha_termino"];
    $fecha_creacion = date('Y-m-d H:i:s');
    
    // Obtener un ID de funcionario dinámicamente para evitar errores de Llave Foránea (Foreign Key)
    $func = $db->query("SELECT id_funcionario FROM funcionario_municipal LIMIT 1");
    if (empty($func)) {
        throw new Exception("Error de Base de Datos: No hay funcionarios municipales registrados. Debes tener al menos un funcionario en tu BD para poder publicar votaciones.");
    }
    $id_funcionario = $func[0]['id_funcionario']; 

    // Formatear fecha si viene de un datetime-local
    if ($fecha_termino != "") {
        $fecha_termino = str_replace("T", " ", $fecha_termino) . ":00";
    }

    $tipo_estado = 'activa';

    try {
        // Insertar Consulta
        $consulta = "INSERT INTO consulta_votacion(titulo, fecha_creacion, fecha_termino, pregunta, descripcion, tipo_consulta, tipo_estado, id_funcionario) 
                     VALUES('$titulo', '$fecha_creacion', '$fecha_termino', '$pregunta', '$descripcion', '$tipo_consulta', '$tipo_estado', $id_funcionario)";
        $db->query($consulta);

        // Obtener el ID de la consulta insertada
        $res = $db->query("SELECT MAX(id_consulta) as max_id FROM consulta_votacion");
        $id_consulta = $res[0]['max_id'];

        // Insertar Alternativas
        if (isset($_POST['alternativas']) && is_array($_POST['alternativas'])) {
            $orden = 1;
            foreach ($_POST['alternativas'] as $texto_alternativa) {
                if (trim($texto_alternativa) !== "") {
                    // Asegurar que no hayan problemas con comillas
                    $texto_escapado = addslashes($texto_alternativa);
                    $db->query("INSERT INTO alternativa_consulta(orden_alternativa, texto_alternativa, id_consulta_votacion) 
                                VALUES($orden, '$texto_escapado', $id_consulta)");
                    $orden++;
                }
            }
        }

        header("Location: ?ruta=crear_votacion&success=1");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Votación - SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Asegurarse de cargar los estilos generales del panel (ruta correcta en proyecto local) -->
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city-main/public/assets/css/panel.css">
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/panel.css"> 
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . "/../../views/layout/sidebar.php"; ?>

            <main class="col-md-10 ms-sm-auto px-4 py-3">
                    <div class="d-flex justify-content-between">
                       <div>
                            <h3 class="fw-bold mb-1">Gestión de Consultas ciudadanas</h3>
                            <small class="text-muted">Registra una nueva consulta ciudadana</small>
                        </div>

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
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        <strong>¡Éxito!</strong> La votación y sus alternativas se han guardado correctamente en la base de datos.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger rounded-3 shadow-sm">
                        Error al guardar: <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="post-box p-4 border rounded-4 bg-white shadow-sm mx-auto" style="max-width: 800px;"> 
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Título de la Consulta</label>
                            <input type="text" name="titulo" class="form-control rounded-pill px-3 py-2 bg-light border-0" placeholder="Ej: Mejoramiento de Plaza Central" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Pregunta Principal</label>
                            <input type="text" name="pregunta" class="form-control rounded-pill px-3 py-2 bg-light border-0" placeholder="¿Qué diseño prefieres para la plaza?" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Descripción (Opcional)</label>
                            <textarea name="descripcion" class="form-control rounded-4 px-3 py-2 bg-light border-0" placeholder="Detalles adicionales sobre la consulta..." rows="3"></textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Tipo</label>
                                <select name="tipo_consulta" class="form-select rounded-pill px-3 py-2 bg-light border-0" required>
                                    <option value="votacion">Votación Vinculante</option>
                                    <option value="consulta">Consulta Ciudadana</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Fecha de Término</label>
                                <input type="datetime-local" name="fecha_termino" class="form-control rounded-pill px-3 py-2 bg-light border-0" required>
                            </div>
                        </div>

                        <div class="p-3 border rounded-4 bg-light mb-4 shadow-sm">
                            <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-list-task"></i> Alternativas / Opciones de Respuesta</h6>
                            <div id="listaAlternativas">
                                <!-- Alternativas generadas con JS -->
                            </div>
                            <button type="button" class="btn btn-outline-primary rounded-pill btn-sm mt-3 fw-semibold px-3" onclick="agregarAlternativa()">
                                <i class="bi bi-plus-circle"></i> Añadir otra opción
                            </button>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow">
                                <i class="bi bi-send"></i> Publicar Votación
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS para componentes como las alertas -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    <script>
        let alternativaCount = 0;

        function agregarAlternativa() {
            alternativaCount++;
            const div = document.createElement('div');
            div.className = 'd-flex gap-2 mb-2 alternativa-item';
            div.id = `alt-${alternativaCount}`;
            
            div.innerHTML = `
                <input type="text" name="alternativas[]" class="form-control rounded-pill px-3 py-2" placeholder="Escribe la opción ${alternativaCount}" required>
                <button type="button" class="btn btn-danger rounded-pill px-3 shadow-sm" onclick="eliminarAlternativa('alt-${alternativaCount}')" title="Eliminar opción">
                    <i class="bi bi-trash3"></i>
                </button>
            `;
            
            document.getElementById('listaAlternativas').appendChild(div);
        }

        function eliminarAlternativa(id) {
            const elemento = document.getElementById(id);
            if(document.querySelectorAll('.alternativa-item').length > 2) {
                elemento.remove();
            } else {
                alert('Debes tener al menos 2 alternativas obligatorias.');
            }
        }

        // Inicializar con 2 alternativas por defecto
        agregarAlternativa();
        agregarAlternativa();
    </script>
</body>
</html>
