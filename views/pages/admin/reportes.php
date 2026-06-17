<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reportes - SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #3d71ff;
            --bg-light: #f8fafc;
            --sidebar-text: #64748b;
            --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #0f172a;
        }

        .sidebar {
            background: #ffffff;
            height: 100vh;
            border-right: 1px solid #f1f5f9;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .nav-link {
            color: var(--sidebar-text);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .nav-link.active {
            background-color: #f0f4ff;
            color: var(--primary-blue);
        }

        /* UI Components */
        .shadow-card { box-shadow: var(--shadow-soft); border: none; border-radius: 24px; }
        
        /* Table Styling - border-0 en tr */
        .table-custom thead th {
            background-color: #f8fafc;
            border: 0 !important;
            color: #64748b;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 18px 24px;
        }

        .table-custom tbody tr td {
            border: 0 !important;
            padding: 18px 24px;
            vertical-align: middle;
        }

        /* Status Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pendiente { background: #fff7ed; color: #9a3412; }
        .status-revision { background: #eff6ff; color: #1e40af; }
        .status-resuelto { background: #f0fdf4; color: #166534; }

        .btn-action-soft {
            width: 35px; height: 35px; border-radius: 10px; border: none; 
            background: #f1f5f9; color: #64748b; transition: 0.2s;
        }
        .btn-action-soft:hover { background: #e2e8f0; color: #0f172a; }

        .img-report-preview {
            width: 45px; height: 45px; border-radius: 10px; object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
         <?php include __DIR__ . "../../../layout/sidebar.php"; ?>
        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto px-md-5">
            <header class="d-flex justify-content-between align-items-center py-4">
                <div>
                    <h2 class="fw-bold mb-0">Gestión de Reportes Ciudadanos</h2>
                    <p class="text-muted small">Administra, deriva y supervisa la resolución de incidentes en la comuna.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary rounded-pill px-3 fw-bold shadow-sm">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </button>
                    <button class="btn btn-outline-secondary rounded-pill px-3 fw-bold shadow-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </button>
                </div>
            </header>

            <!-- Filtros Rápidos -->
            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                            <i class="bi bi-list-task fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">128</h4>
                            <span class="text-muted tiny">Total Reportes</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning p-3 rounded-4 me-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">42</h4>
                            <span class="text-muted tiny">Pendientes</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-card p-3 d-flex flex-row align-items-center">
                        <div class="icon-box bg-success bg-opacity-10 text-success p-3 rounded-4 me-3">
                            <i class="bi bi-check2-all fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">86</h4>
                            <span class="text-muted tiny">Resueltos</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Gestión -->
            <div class="card shadow-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Reporte</th>
                                <th>Categoría</th>
                                <th>Derivación</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Fila 1 -->
                            <tr class="border-0">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.unsplash.com/photo-1584464431734-783307763690?w=100" class="img-report-preview me-3">
                                        <div>
                                            <div class="fw-bold small">Bache Profundo</div>
                                            <div class="text-muted tiny">Av. Libertad #450</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small fw-semibold">Obras</span></td>
                                <td><span class="text-muted small">Dirección de Obras</span></td>
                                <td><span class="badge-status status-pendiente">Pendiente</span></td>
                                <td class="small">12 Mayo, 2026</td>
                                <td class="text-end">
                                    <button class="btn-action-soft me-1" title="Ver en Mapa"><i class="bi bi-geo-alt"></i></button>
                                    <button class="btn-action-soft me-1" title="Editar Estado"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn-action-soft text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Fila 2 -->
                            <tr class="border-0">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.unsplash.com/photo-1617112848923-cc22343d6a8d?w=100" class="img-report-preview me-3">
                                        <div>
                                            <div class="fw-bold small">Luminaria Apagada</div>
                                            <div class="text-muted tiny">Plaza de Armas</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small fw-semibold">Luminaria</span></td>
                                <td><span class="text-muted small">Seguridad Pública</span></td>
                                <td><span class="badge-status status-resuelto">Resuelto</span></td>
                                <td class="small">10 Mayo, 2026</td>
                                <td class="text-end">
                                    <button class="btn-action-soft me-1"><i class="bi bi-geo-alt"></i></button>
                                    <button class="btn-action-soft me-1"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn-action-soft text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Fila 3 -->
                            <tr class="border-0">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=100" class="img-report-preview me-3">
                                        <div>
                                            <div class="fw-bold small">Basural Ilegal</div>
                                            <div class="text-muted tiny">Sector Norte Errázuriz</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="small fw-semibold">Aseo</span></td>
                                <td><span class="text-muted small">Aseo y Ornato</span></td>
                                <td><span class="badge-status status-revision">En Revisión</span></td>
                                <td class="small">11 Mayo, 2026</td>
                                <td class="text-end">
                                    <button class="btn-action-soft me-1"><i class="bi bi-geo-alt"></i></button>
                                    <button class="btn-action-soft me-1"><i class="bi bi-pencil-square"></i></button>
                                    <button class="btn-action-soft text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>