<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Comercios - Shopeers</title>
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

        /* Sidebar */
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

        .nav-link:hover, .nav-link.active {
            background-color: #f0f4ff;
            color: var(--primary-blue);
        }

        /* Card & UI */
        .shadow-card { box-shadow: var(--shadow-soft); border: none; border-radius: 20px; }
        .commerce-img { width: 45px; height: 45px; border-radius: 12px; object-fit: cover; background: #f1f5f9; }
        
        .badge-verified { background: #dcfce7; color: #15803d; font-size: 0.7rem; border-radius: 6px; padding: 4px 8px; }
        .badge-pending { background: #fef9c3; color: #a16207; font-size: 0.7rem; border-radius: 6px; padding: 4px 8px; }
        
        .btn-action-soft {
            width: 34px; height: 34px; border-radius: 10px; border: none; 
            background: #f8fafc; color: #64748b; transition: 0.2s;
        }
        .btn-action-soft:hover { background: #e2e8f0; color: #0f172a; }

        .search-container { background: #ffffff; border-radius: 50px; }
        .modal-content { border-radius: 24px; border: none; }
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
                <div class="search-wrapper w-50">
                    <div class="input-group search-container shadow-sm px-3">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 bg-transparent" placeholder="Buscar por nombre, RUT o rubro...">
                    </div>
                </div>
                <a href="../emprendedores_comercio/visualizar_emprendores_comercio.html" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"  data-bs-target="#commerceModal">
                    <i class="bi bi-plus-lg me-2"></i> Visualizar comercios y emprendedores
                </a>
                <a href="../emprendedores_comercio/emprendedores_comercio.html" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"  data-bs-target="#commerceModal">
                    <i class="bi bi-plus-lg me-2"></i> Registrar Negocio
                </a>

            </header>

            <div class="mb-4">
                <h2 class="fw-bold mb-0">Comercios y Emprendedores</h2>
                <p class="text-muted small">Fomento productivo y validación de comercios locales.</p>
            </div>

            <!-- Stats Rápidas -->

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-card p-3 rounded-4">
                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span class="small fw-semibold">Total Registrados</span>
                            <i class="bi bi-exclamation-triangle-fill text-warning opacity-50"></i>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0">1,240</h3>
                            <span class="badge-success-soft"><i class="bi bi-caret-up-fill me-1"></i>22%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-card p-3 rounded-4">
                        <div class="d-flex justify-content-between text-muted mb-2"><span class="small fw-semibold">Pendientes Verificación</span><i class="bi bi-check2-square text-info opacity-50"></i></div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0">18</h3>
                            <span class="badge-success-soft"><i class="bi bi-caret-up-fill me-1"></i>12%</span>
                        </div>
                    </div>
                </div>

            </div>



            <!-- Listado -->
            <div class="card border-0 shadow-card rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="tiny text-muted">
                                <th class="border-0 px-4 py-3">NEGOCIO / DUEÑO</th>
                                <th class="border-0 py-3">RUBRO</th>
                                <th class="border-0 py-3">TIPO</th>
                                <th class="border-0 py-3">ESTADO</th>
                                <th class="border-0 py-3 text-end px-4">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            <!-- Item 1 -->
                            <tr>
                                <td class="px-4 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="commerce-img d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-shop text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">Café del Barrio</div>
                                            <div class="text-muted tiny">RUT: 76.443.221-K</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0"><span class="small">Gastronomía</span></td>
                                <td class="border-0"><span class="badge bg-outline-primary text-primary border border-primary-subtle tiny px-2">Comercio Fijo</span></td>
                                <td class="border-0"><span class="badge-verified fw-bold">● Verificado</span></td>
                                <td class="text-end px-4 border-0">
                                    <button class="btn-action-soft"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action-soft text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Item 2 -->
                            <tr>
                                <td class="px-4 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="commerce-img d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-handbag text-success"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">Artesanías BioBio</div>
                                            <div class="text-muted tiny">RUT: 18.223.445-2</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0"><span class="small">Artesanía</span></td>
                                <td class="border-0"><span class="badge bg-outline-success text-success border border-success-subtle tiny px-2">Emprendedor</span></td>
                                <td class="border-0"><span class="badge-pending fw-bold">● Pendiente</span></td>
                                <td class="text-end px-4 border-0">
                                    <button class="btn-action-soft"><i class="bi bi-check-circle text-success"></i></button>
                                    <button class="btn-action-soft"><i class="bi bi-pencil"></i></button>
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

<!-- Modal Registro -->
<div class="modal fade" id="commerceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Registrar Nuevo Comercio/Emprendedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nombre de Fantasía</label>
                        <input type="text" class="form-control rounded-3" placeholder="Ej: Pizzería Roma">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">RUT Empresa / Persona</label>
                        <input type="text" class="form-control rounded-3" placeholder="11.222.333-4">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Tipo de Negocio</label>
                        <select class="form-select rounded-3">
                            <option>Comercio Establecido (Local)</option>
                            <option>Emprendedor / Pyme Online</option>
                            <option>Feria Itinerante</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Categoría / Rubro</label>
                        <select class="form-select rounded-3">
                            <option>Gastronomía</option>
                            <option>Servicios</option>
                            <option>Vestuario</option>
                            <option>Hogar</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Dirección Comercial (o área de operación)</label>
                        <input type="text" class="form-control rounded-3" placeholder="Av. Principal #123">
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>