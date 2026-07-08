<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();

// Obtener todas las votaciones activas
$votaciones = $db->query("SELECT * FROM consulta_votacion WHERE tipo_estado = 'activa' ORDER BY fecha_creacion DESC");
?>

<div class="container mt-4">
    <a href="?ruta=dashboard" class="btn btn-sm btn-outline-secondary mb-3 rounded-pill">
        ← Volver al Inicio
    </a>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🗳️ Consultas y Votaciones Activas</h2>
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
                                    📊 Ver Resultados
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
