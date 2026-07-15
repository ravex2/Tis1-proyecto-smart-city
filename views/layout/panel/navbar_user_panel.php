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
<div class="dropdown text-end">
    <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        <div class="text-start">
            <div class="fw-semibold">
                <?= htmlspecialchars($usuarioLogeado['nombre'] . ' ' . $usuarioLogeado['apellido']) ?>
            </div>
            <small class="text-muted">
                <?= htmlspecialchars($usuarioLogeado['correo']) ?>
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

