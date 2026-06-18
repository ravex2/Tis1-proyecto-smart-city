<?php $rutaActual = $_GET['ruta'] ?? 'inicio'; ?>

<nav class="col-md-2 d-none d-md-block sidebar px-4">
    <div class="sidebar-sticky">

        <div class="logo-area my-4 d-flex align-items-center">
            <div class="logo-icon me-2">
                <i class="bi bi-intersect fs-3 text-primary"></i>
            </div>
            <span class="fw-bold fs-4">Administrador</span>
        </div>

        <ul class="nav flex-column gap-1">

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'dashboard' ? 'active' : '' ?>" href="?ruta=dashboard">
                    <i class="bi bi-grid-fill me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'reportes' ? 'active' : '' ?>" href="?ruta=reportes">
                    <i class="bi bi-megaphone me-2"></i> Reportes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'sector' ? 'active' : '' ?>" href="?ruta=sector">
                    <i class="bi bi-map me-2"></i> Sector
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'votaciones' ? 'active' : '' ?>" href="?ruta=votaciones">
                    <i class="bi bi-check2-square me-2"></i> Votaciones
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'comercio' ? 'active' : '' ?>" href="?ruta=comercio">
                    <i class="bi bi-shop me-2"></i> Emprendedores y Comercio
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'rubros' ? 'active' : '' ?>" href="?ruta=rubros">
                    <i class="bi bi-tag me-2"></i> Rubro
                </a>
            </li>

        </ul>

        <h6 class="sidebar-heading px-3 mt-4 mb-2 text-muted text-uppercase small fw-bold">
            Gestión
        </h6>

        <ul class="nav flex-column gap-1">

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'crear_publicacion' ? 'active' : '' ?>" href="?ruta=crear_publicacion">
                    <i class="bi bi-newspaper me-2"></i> Publicaciones
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'usuarios' ? 'active' : '' ?>" href="?ruta=usuarios">
                    <i class="bi bi-person-gear me-2"></i> Roles
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $rutaActual === 'departamentos' ? 'active' : '' ?>" href="?ruta=departamentos">
                    <i class="bi bi-people me-2"></i> Departamentos
                </a>
            </li>

        </ul>
    </div>
</nav>