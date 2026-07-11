<?php include __DIR__ . "../../../layout/panel/header.php" ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
         <?php include __DIR__ . "../../../layout/sidebar.php"; ?>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-5 main-content bg-light-soft">
            <header class="d-flex justify-content-between align-items-center py-4">
                <div class="search-wrapper w-50">
                    <div class="input-group search-container border-0 shadow-sm rounded-pill px-3">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 bg-transparent" placeholder="Buscar reportes o ciudadanos...">
                        <span class="kbd-hint border rounded px-2 my-auto small text-muted">⌘ K</span>
                    </div>
                </div>
                <div class="header-actions d-flex align-items-center gap-3">
                    <button class="btn-icon-soft"><i class="bi bi-brightness-high"></i></button>
                    <button class="btn-icon-soft"><i class="bi bi-bell"></i></button>
                    <div class="user-avatar ms-2">
                        <img src="https://i.pravatar.cc/150?u=admin" width="40" height="40" class="rounded-circle" alt="User">
                    </div>
                </div>
            </header>

            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Gestión Municipal</h2>
                    <p class="text-muted small mb-0">Analítica de interacción y reportes ciudadanos.</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-white shadow-sm rounded-pill px-3 dropdown-toggle btn-sm" type="button">
                            <i class="bi bi-calendar3 me-2"></i> Período: Abril 2026
                        </button>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 btn-sm shadow-primary"><i class="bi bi-file-earmark-pdf me-2"></i> Exportar PDF</button>
                    <button class="btn btn-success text-white rounded-pill px-4 btn-sm shadow-sm" style="background-color: #10b981; border: none;"><i class="bi bi-file-earmark-excel me-2"></i> Excel</button>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-card p-3 rounded-4">
                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span class="small fw-semibold">Reportes Ciudadanos</span>
                            <i class="bi bi-exclamation-triangle-fill text-warning opacity-50"></i>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0">482</h3>
                            <span class="badge-success-soft"><i class="bi bi-caret-up-fill me-1"></i>12%</span>
                        </div>
                        <p class="text-muted tiny mt-2 mb-0">24 pendientes de revisión</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-card p-3 rounded-4">
                        <div class="d-flex justify-content-between text-muted mb-2"><span class="small fw-semibold">Participación Votos</span><i class="bi bi-check2-square text-info opacity-50"></i></div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0">3,150</h3>
                            <span class="badge-success-soft"><i class="bi bi-caret-up-fill me-1"></i>22%</span>
                        </div>
                        <p class="text-muted tiny mt-2 mb-0">Consulta: Obras Verdes 2026</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-card p-3 rounded-4">
                        <div class="d-flex justify-content-between text-muted mb-2"><span class="small fw-semibold">Interacciones Feed</span><i class="bi bi-chat-heart-fill text-danger opacity-50"></i></div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0">8,924</h3>
                            <span class="badge-success-soft"><i class="bi bi-caret-up-fill me-1"></i>5,2%</span>
                        </div>
                        <p class="text-muted tiny mt-2 mb-0">Likes y comentarios en noticias</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-card p-3 rounded-4">
                        <div class="d-flex justify-content-between text-muted mb-2"><span class="small fw-semibold">Nuevos Comercios</span><i class="bi bi-shop text-primary opacity-50"></i></div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0">156</h3>
                            <span class="badge-success-soft"><i class="bi bi-caret-up-fill me-1"></i>10%</span>
                        </div>
                        <p class="text-muted tiny mt-2 mb-0">vs. 142 mes anterior</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-card p-4 rounded-4">
                        <h6 class="fw-bold mb-4">Evolución de Interacción Ciudadana</h6>
                        <div class="d-flex align-items-baseline gap-3 mb-4">
                            <h2 class="fw-bold">Interacción Activa</h2>
                            <span class="text-success small fw-bold"><i class="bi bi-arrow-up-right me-1"></i>18.4% <span class="fw-normal text-muted ms-1">crecimiento mensual</span></span>
                        </div>
                        <div style="height: 300px;">
                            <canvas id="interaccionChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Ranking Sectores -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-card p-4 rounded-4 h-100 text-center">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0">Participación por Sector</h6>
                            <i class="bi bi-three-dots text-muted"></i>
                        </div>
                        <div style="height: 250px;">
                            <canvas id="sectorChart"></canvas>
                        </div>
                        <p class="text-muted small mt-3">El <span class="fw-bold text-dark">Barrio Centro</span> es el más activo con un 35% de los reportes totales.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

// codigo temporal para poder visualizar los graficos.
const interCtx = document.getElementById('interaccionChart').getContext('2d');
const gradient = interCtx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(61, 113, 255, 0.2)');
gradient.addColorStop(1, 'rgba(61, 113, 255, 0)');

new Chart(interCtx, {
    type: 'line',
    data: {
        labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
        datasets: [{
            label: 'Reportes e Interacciones',
            data: [2100, 3400, 2800, 4500],
            borderColor: '#3d71ff',
            borderWidth: 3,
            fill: true,
            backgroundColor: gradient,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3d71ff'
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 10 } } },
            x: { border: { display: false }, grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } }
        }
    }
});

// Gráfico de Barras 
const sectorCtx = document.getElementById('sectorChart').getContext('2d');
new Chart(sectorCtx, {
    type: 'bar',
    data: {
        labels: ['Centro', 'Norte', 'Oriente', 'Sur', 'Rural'],
        datasets: [{
            data: [85, 42, 63, 31, 15],
            backgroundColor: (context) => {
                return context.dataIndex === 0 ? '#3d71ff' : '#e2e8f0';
            },
            borderRadius: 8,
            barThickness: 20
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { display: false },
            x: { grid: { display: false }, border: { display: false } }
        }
    }
});
</script>

<?php include __DIR__ . "../../../layout/panel/footer.php" ?>