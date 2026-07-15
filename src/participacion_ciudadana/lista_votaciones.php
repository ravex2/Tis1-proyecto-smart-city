<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();

// Obtener todas las votaciones activas
$votaciones = $db->query("SELECT * FROM consulta_votacion WHERE tipo_estado = 'activa' ORDER BY fecha_creacion DESC");
?>

<!doctype html>
<html lang="es">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
    </head>

    <body class="bg-light" style="font-family: sans-serif;">
        <?php
            require_once __DIR__ . "/../../views/layout/navbar_user.php";
        ?>
        <div class="container mt-4">

            <div class="d-flex justify-content-between align-items-center mb-4 "  style="margin-top: 90px; max-width: 1100px;">
                <h2>Consultas y Votaciones Activas</h2>
            </div>

            <div class="row">
                <?php if (empty($votaciones)): ?>
                <div class="col-12">
                        <div class="alert alert-info text-center">
                            No hay consultas o votaciones activas en este momento.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($votaciones as $votacion): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2 rounded-pill"><?= htmlspecialchars($votacion['tipo_consulta']) ?></span>
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($votacion['titulo']) ?></h5>
                                    <p class="card-text text-muted text-truncate"><?= htmlspecialchars($votacion['descripcion']) ?></p>
                                    
                                    <hr>
                                    <p class="mb-1"><strong>Cierra el:</strong> <?= date('d/m/Y H:i', strtotime($votacion['fecha_termino'])) ?></p>
                                    
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="?ruta=ver_votacion&id=<?= $votacion['id_consulta'] ?>" class="btn btn-primary w-100 rounded-pill fw-bold">
                                            Participar / Votar
                                        </a>
                                        <a href="?ruta=resultados_votacion&id=<?= $votacion['id_consulta'] ?>" class="btn btn-outline-info w-100 rounded-pill fw-bold">
                                            Ver Resultados
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>

