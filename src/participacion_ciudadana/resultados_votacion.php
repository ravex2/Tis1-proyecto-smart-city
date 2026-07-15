<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();

$id_consulta = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validar y obtener datos de la consulta
$consultas = $db->query("SELECT * FROM consulta_votacion WHERE id_consulta = $id_consulta");
if (empty($consultas)) {
    echo "<div class='alert alert-danger m-4'>La votación solicitada no existe.</div>";
    exit;
}
$consulta = $consultas[0];

// Obtener los resultados agrupados por alternativa
$resultados = $db->query("
    SELECT ac.texto_alternativa, COUNT(v.id_voto) as total_votos 
    FROM alternativa_consulta ac 
    LEFT JOIN voto v ON ac.id_alternativa = v.id_alternativa 
    WHERE ac.id_consulta_votacion = $id_consulta 
    GROUP BY ac.id_alternativa
");

$total_votos_totales = 0;
$labels = [];
$data = [];

foreach ($resultados as $res) {
    $total_votos_totales += $res['total_votos'];
    $labels[] = $res['texto_alternativa'];
    $data[] = $res['total_votos'];
}
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex gap-2 mb-3">
                <a href="?ruta=lista_votaciones" class="btn btn-sm btn-outline-secondary rounded-pill">
                    ← Volver a Votaciones
                </a>
                <a href="?ruta=dashboard" class="btn btn-sm btn-outline-primary rounded-pill">
                    🏠 Volver al Inicio
                </a>
            </div>
            
            <h3 class="card-title fw-bold text-center mb-1">Resultados: <?= htmlspecialchars($consulta['titulo']) ?></h3>
            <p class="text-muted text-center mb-4">Total de votos emitidos: <strong><?= $total_votos_totales ?></strong></p>
            
            <?php if ($total_votos_totales == 0): ?>
                <div class="alert alert-warning text-center rounded-4 p-4">
                    <h5 class="fw-bold mb-0">Aún no hay votos registrados para esta consulta.</h5>
                </div>
            <?php else: ?>
                <div class="row align-items-center mt-4">
                    <!-- Gráfico -->
                    <div class="col-md-7 mb-4">
                        <canvas id="resultadosChart"></canvas>
                    </div>
                    
                    <!-- Leyenda / Porcentajes -->
                    <div class="col-md-5">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($resultados as $res): 
                                $porcentaje = ($res['total_votos'] / $total_votos_totales) * 100;
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 mb-2 rounded-3 bg-light">
                                    <span class="fw-medium"><?= htmlspecialchars($res['texto_alternativa']) ?></span>
                                    <span class="badge bg-primary rounded-pill" style="font-size: 14px;">
                                        <?= number_format($porcentaje, 1) ?>% (<?= $res['total_votos'] ?> votos)
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Incluir Chart.js para el gráfico -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if ($total_votos_totales > 0): ?>
        var ctx = document.getElementById('resultadosChart').getContext('2d');
        var resultadosChart = new Chart(ctx, {
            type: 'bar', // Puede ser 'doughnut', 'pie', o 'bar'
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Cantidad de Votos',
                    data: <?= json_encode($data) ?>,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    <?php endif; ?>
});
</script>
