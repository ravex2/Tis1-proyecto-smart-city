<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }   

    if (!isset($_SESSION['user'])) {
        header('Location: ?ruta=login');
        exit;
    }   

    $usuarioLogeado = $_SESSION['user'] ?? null;
    $rutaActual = $_GET['ruta'] ?? 'inicio';
?>

<nav class="navbar navbar-expand-sm navbar-light bg-light shadow-sm fixed-top w-100 px-3">
    <a class="navbar-brand fw-bold" href="?ruta=publicaciones">SMART CITY</a>
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
                <a class="nav-link fw-semibold <?= $rutaActual === 'publicaciones' ? 'border border-primary text-primary fw-semibold bg-primary-subtle rounded' : '' ?>" href="?ruta=publicaciones">
                    Inicio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold <?= $rutaActual === 'crear_reporte' ? 'border border-primary text-primary fw-semibold bg-primary-subtle rounded' : '' ?>" href="?ruta=crear_reporte">
                    Reportes Ciudadanos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold <?= $rutaActual === 'comercio' ? 'border border-primary text-primary fw-semibold bg-primary-subtle rounded' : '' ?>" href="?ruta=comercio">
                    Comercio Local
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold <?= $rutaActual === 'listado_votaciones' ? 'border border-primary text-primary fw-semibold bg-primary-subtle rounded' : '' ?>" href="?ruta=listado_votaciones">
                    Consultas Ciudadanas
                </a>
            </li>
            
        </ul>
        <div class="dropdown text-end">
            <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <div class="text-start me-2">
                    <div class="fw-semibold text-dark leading-none" style="font-size: 0.9rem;">
                        <?= htmlspecialchars($usuarioLogeado['nombre'] . ' ' . $usuarioLogeado['apellido']) ?>
                    </div>
                    <small class="text-muted d-block" style="font-size: 0.75rem; margin-top: -2px;">
                        <?= htmlspecialchars($usuarioLogeado['correo']) ?>
                    </small>
                </div>
                <div>
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuarioLogeado['nombre'].' '.$usuarioLogeado['apellido']) ?>&background=3d71ff&color=fff&rounded=true&size=40"
                        class="rounded-circle" width="40" height="40" alt="usuario">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item text-danger" href="?ruta=logout">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>