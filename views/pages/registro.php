<?php
include __DIR__ . "/../layout/header.php";

?>

<div class="container-fluid p-0 overflow-hidden">
    <div class="row g-0 vh-100">
        <div class="col-lg-7 d-none d-lg-block left-side-container">
            <div class="diagonal-bg"></div>
            
            <div class="overlay-content">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="fs-3 fw-bold">Portal Ciudadano</span>
                </div>
                <h1 class="display-4 fw-bold">Tu comuna,<br>más cerca.</h1>
            </div>
        </div>
        <div class="col-lg-5 d-flex align-items-center justify-content-center bg-white">
            <div class="login-box p-4 p-md-5 w-100" style="max-width: 450px;">
                <div class="mb-5">
                    <h2 class="fw-bold text-dark">Bienvenido de nuevo Registrate</h2>
                    <p class="text-muted">Ingresa tus credenciales para acceder al portal ciudadano.</p>
                </div>

                <form id="loginForm">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Correo Electrónico</label>
                        <div class="input-group custom-input-group shadow-sm">
                            <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-0 py-3" placeholder="nombre@ejemplo.cl" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <div class="input-group custom-input-group shadow-sm">
                            <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" class="form-control border-0 py-3" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Recordarme</label>
                        </div>
                        <a href="#" class="small text-primary fw-bold text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-primary">
                        Iniciar Sesión
                    </button>

                    <div class="text-center mt-5">
                        <p class="text-muted small">¿No tienes una cuenta aún? <br>
                            <a href="?ruta=login" class="text-primary fw-bold text-decoration-none">Regístrate como ciudadano aquí</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . "/../layout/footer.php";
?>