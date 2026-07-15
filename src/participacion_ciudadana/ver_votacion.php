<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();

$id_consulta = isset($_GET['id']) ? intval($_GET['id']) : 0;
$rut_usuario = 87654321; // TODO: Cambiar por $_SESSION['user']['rut_usuario'] cuando el login esté activo

// Validar y obtener datos de la consulta
$consultas = $db->query("SELECT * FROM consulta_votacion WHERE id_consulta = $id_consulta");
if (empty($consultas)) {
    echo "<div class='alert alert-danger m-4'>La votación solicitada no existe.</div>";
    exit;
}
$consulta = $consultas[0];
$alternativas = $db->query("SELECT * FROM alternativa_consulta WHERE id_consulta_votacion = $id_consulta");

// Verificar si el usuario ya votó en esta consulta para evitar duplicados
$check_voto = $db->query("
    SELECT p.rut_usuario 
    FROM participa p 
    JOIN participacion pa ON p.id_participacion = pa.id_participacion 
    JOIN voto v ON pa.id_voto = v.id_voto 
    JOIN alternativa_consulta ac ON v.id_alternativa = ac.id_alternativa 
    WHERE ac.id_consulta_votacion = $id_consulta 
    AND p.rut_usuario = $rut_usuario
");
$ya_voto = count($check_voto) > 0;

$mensaje = "";
$error = "";

// Procesar el formulario de voto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_alternativa'])) {
    if ($ya_voto) {
        $error = "Ya has registrado un voto para esta consulta. No se permiten votos duplicados.";
    } else {
        $id_alternativa = intval($_POST['id_alternativa']);
        $fecha_actual = date('Y-m-d H:i:s');

        try {
            // 1. Registrar el Voto
            $db->query("INSERT INTO voto (fecha_voto, id_alternativa) VALUES ('$fecha_actual', $id_alternativa)");
            
            // Obtener el ID del voto recién insertado
            $res_voto = $db->query("SELECT MAX(id_voto) as max_id FROM voto");
            $id_voto = $res_voto[0]['max_id'];

            // 2. Registrar la Participación
            $db->query("INSERT INTO participacion (fecha_participacion, id_voto) VALUES ('$fecha_actual', $id_voto)");
            $res_part = $db->query("SELECT MAX(id_participacion) as max_id FROM participacion");
            $id_participacion = $res_part[0]['max_id'];

            // 3. Vincular la Participación con el Usuario (Rut)
            $db->query("INSERT INTO participa (id_participacion, rut_usuario) VALUES ($id_participacion, $rut_usuario)");

            $mensaje = "¡Tu voto ha sido registrado exitosamente!";
            $ya_voto = true; // Actualizar estado visual
        } catch (Exception $e) {
            $error = "Error al guardar el voto: " . $e->getMessage();
        }
    }
}
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
    
    <body class="bg-light" style="font-family: sans-serif;">
        <?php include __DIR__ . "/../../views/layout/navbar_user.php"; ?>
        <div class="container mb-5"style="margin-top: 90px; max-width: 1100px;" >
            <?php if ($mensaje): ?>
                <div class="alert alert-success fw-bold">✔️ <?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger fw-bold">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex gap-2 mb-3">
                        <a href="?ruta=listado_votaciones" class="btn btn-sm btn-outline-secondary rounded-pill">
                            ← Volver a Votaciones
                        </a>
                    </div>
                    
                    <h3 class="card-title fw-bold mb-3"><?= htmlspecialchars($consulta['titulo']) ?></h3>
                    <p class="text-muted mb-4"><?= htmlspecialchars($consulta['descripcion']) ?></p>
                    
                    <div class="bg-light p-4 rounded-4 mb-4">
                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($consulta['pregunta']) ?></h5>
                    </div>

                    <?php if ($ya_voto): ?>
                        <div class="alert alert-info text-center rounded-4 p-4">
                            <h5 class="fw-bold mb-2">Ya participaste en esta votación</h5>
                            <p class="mb-0">Gracias por tu participación. Tu voto ha sido registrado y asegurado por tu RUT.</p>
                        </div>
                    <?php else: ?>
                        <form action="" method="POST">
                            <div class="mb-4">
                                <?php foreach ($alternativas as $alt): ?>
                                    <div class="form-check custom-radio mb-3 p-3 border rounded-3 hover-bg-light">
                                        <input class="form-check-input ms-1 me-3" type="radio" name="id_alternativa" 
                                            id="alt_<?= $alt['id_alternativa'] ?>" 
                                            value="<?= $alt['id_alternativa'] ?>" required>
                                        <label class="form-check-label w-100 fw-medium" for="alt_<?= $alt['id_alternativa'] ?>" style="cursor: pointer;">
                                            <?= htmlspecialchars($alt['texto_alternativa']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                                Votar Ahora
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>


<style>
    .custom-radio { transition: all 0.2s; }
    .custom-radio:hover { background-color: #f8f9fa; border-color: #0d6efd !important; }
    .form-check-input:checked + .form-check-label { color: #0d6efd; font-weight: bold; }
</style>
