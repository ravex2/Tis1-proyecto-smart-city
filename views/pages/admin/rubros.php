<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Rubros - SmartCity</title>
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

        .icon-shape {
            width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; font-size: 1.2rem;
        }

        .btn-action-soft {
            width: 35px; height: 35px; border-radius: 10px; border: none; 
            background: #f1f5f9; color: #64748b; transition: 0.2s;
        }
        .btn-action-soft:hover { background: #e2e8f0; color: #0f172a; }

        .modal-content { border-radius: 24px; border: none; padding: 10px; }
        .form-control, .form-select { border-radius: 12px; padding: 12px; border: 1px solid #f1f5f9; background: #f8fafc; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include __DIR__ . "../../../layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-md-5">
            <header class="d-flex justify-content-between align-items-center py-4">
                <div>
                    <h2 class="fw-bold mb-0">Rubros Comerciales</h2>
                    <p class="text-muted small">Define las categorías para clasificar el comercio local y emprendimientos.</p>
                </div>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#rubroModal">
                    <i class="bi bi-plus-lg me-2"></i> Nuevo Rubro
                </button>
            </header>

            <div class="card shadow-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Icono</th>
                                <th>Nombre del Rubro</th>
                                <th>Descripción</th>
                                <th>Negocios Asociados</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-0">
                                <td><div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="bi bi-egg-fried"></i></div></td>
                                <td><span class="fw-bold small">Gastronomía</span></td>
                                <td><span class="text-muted small">Restaurantes, cafeterías y foodtrucks locales.</span></td>
                                <td><span class="badge bg-light text-dark rounded-pill px-3">24 Negocios</span></td>
                                <td class="text-end">
                                    <button class="btn-action-soft me-1"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action-soft text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="border-0">
                                <td><div class="icon-shape bg-success bg-opacity-10 text-success"><i class="bi bi-scissors"></i></div></td>
                                <td><span class="fw-bold small">Belleza y Estética</span></td>
                                <td><span class="text-muted small">Peluquerías, barberías y centros de estética.</span></td>
                                <td><span class="badge bg-light text-dark rounded-pill px-3">12 Negocios</span></td>
                                <td class="text-end">
                                    <button class="btn-action-soft me-1"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action-soft text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <tr class="border-0">
                                <td><div class="icon-shape bg-warning bg-opacity-10 text-warning"><i class="bi bi-hammer"></i></div></td>
                                <td><span class="fw-bold small">Servicios Técnicos</span></td>
                                <td><span class="text-muted small">Reparaciones de hogar, gasfitería y electricidad.</span></td>
                                <td><span class="badge bg-light text-dark rounded-pill px-3">18 Negocios</span></td>
                                <td class="text-end">
                                    <button class="btn-action-soft me-1"><i class="bi bi-pencil"></i></button>
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

<div class="modal fade" id="rubroModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Configurar Rubro Comercial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold">Nombre del Rubro</label>
                        <input type="text" class="form-control" placeholder="Ej: Artesanía en Madera">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Descripción Corta</label>
                        <textarea class="form-control" rows="3" placeholder="Define qué tipo de negocios incluye este rubro..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Icono (Bootstrap Icons)</label>
                        <input type="text" class="form-control" placeholder="bi-shop">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Color Identificador</label>
                        <select class="form-select">
                            <option value="primary">Azul</option>
                            <option value="success">Verde</option>
                            <option value="warning">Amarillo</option>
                            <option value="danger">Rojo</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">Guardar Rubro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>