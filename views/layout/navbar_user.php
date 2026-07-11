<?php
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    require_once __DIR__ . '/../../models/usuario.php';
    require_once __DIR__ . '/../../models/rol.php';
    
    $usuarioModelo = new Usuario();
    $rolModelo = new Rol();
    
    $usuarios = $usuarioModelo->findAllWithRoles();
    $roles = $rolModelo->findAll();
    


    if (!isset($_SESSION['user'])) {
        header('Location: ?ruta=login');
        exit;
    }   

    $usuarioLogeado = $_SESSION['user'] ?? null;
    $rutaActual = $_GET['ruta'] ?? 'inicio';
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
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-sm navbar-light bg-light rounded shadow-sm">
                <a class="navbar-brand fw-bold" href="#">SMART CITY</a>
                <button
                    class="navbar-toggler d-lg-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapsibleNavId"
                    aria-controls="collapsibleNavId"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= $rutaActual === 'dashboard' ? 'border border-primary text-primary text-primary fw-semibold bg-primary-subtle' : '' ?>" href="?ruta=publicaciones">
                                Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= $rutaActual === 'reportes' ? 'border border-primary text-primary text-primary fw-semibold bg-primary-subtle' : '' ?>" href="?ruta=publicaciones">
                                Reportes Ciudadanos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= $rutaActual === 'comercio' ? ' border border-primary text-primary text-primary fw-semibold bg-primary-subtle' : '' ?>" href="?ruta=comercio">
                                Comercio Local
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= $rutaActual === 'consultas' ? ' border border-primary text-primary text-primary fw-semibold bg-primary-subtle' : '' ?>" href="?ruta=comercio">
                                Consultas Ciudadanas
                            </a>
                        </li>
                    </ul>
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
            </nav>
        </div>

        
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>