<?php

?>
<?php

$ruta = $_GET["ruta"] ?? "login";

if($ruta == "crear_categoria_publicacion"){
    include __DIR__ . "/../src/categorias_publicaciones/crear_categoria_publicacion.php";
    include __DIR__ . "/../src/categorias_publicaciones/leer_categoria_publicacion.php";
    return;
}
if($ruta == "leer_categoria_publicacion"){
    include __DIR__ . "/../src/categorias_publicaciones/leer_categoria_publicacion.php";
    return;
}
if ($ruta == "eliminar_categoria_publicacion") {
    include __DIR__ . "/../src/categorias_publicaciones/eliminar_categoria_publicacion.php";
    return;
}
if ($ruta == "editar_categoria_publicacion") {
    include __DIR__ . "/../src/categorias_publicaciones/editar_categoria_publicacion.php";
    return;
}
if ($ruta == "feed_publicaciones") {
    include __DIR__ . "/../src/publicaciones/feed_publicaciones.php";
    return;
}
if ($ruta == "crear_publicacion") {
    include __DIR__ . "/../src/publicaciones/crear_publicacion.php";
    return;
}
if ($ruta == "leer_publicacion") {
    include __DIR__ . "/../src/publicaciones/leer_publicacion.php";
    return;
}
if ($ruta == "eliminar_publicacion") {
    include __DIR__ . "/../src/publicaciones/eliminar_publicacion.php";
    return;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Municipalidad Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="login-style.css">
    
    <style>
        :root {
            --primary-blue: #3d71ff;
            --bg-light: #ffffff; /* Aseguramos que el fondo base sea blanco */
        }

        /* Contenedor del lado izquierdo */
        .left-side-container {
            background-color: #ffffff; /* Espacio dividido en blanco */
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .diagonal-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Imagen con un degradado más sutil para que no mate el blanco */
            background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(13, 30, 76, 0.2)), 
                        url('https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            
            /* Corte diagonal ajustado para que el área de la imagen sea la que termina en punta */
            /* Dejamos el espacio de la derecha (el 30%) en blanco total */
            clip-path: polygon(0 0, 95% 0, 65% 100%, 0% 100%);
            z-index: 1;
        }

        .overlay-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 10%;
            /* Color de texto oscuro para que resalte sobre el blanco si es necesario, 
            o mantenemos blanco si la imagen es oscura */
            color: white; 
        }

        /* Sombra interna para suavizar la unión con el blanco */
        .diagonal-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            box-shadow: inset -20px 0 30px -20px rgba(0,0,0,0.3);
        }


        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .login-box {
                background: white;
                border-radius: 24px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            }
        }

    </style>
</head>
<body>

<div class="container-fluid p-0 overflow-hidden">
    <div class="row g-0 vh-100">
        <!-- Lado Izquierdo: Imagen con Corte Diagonal -->
        <div class="col-lg-7 d-none d-lg-block left-side-container">
            <div class="diagonal-bg"></div>
            
            <div class="overlay-content">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="fs-3 fw-bold">Portal Ciudadano</span>
                </div>
                <h1 class="display-4 fw-bold">Tu comuna,<br>más cerca.</h1>
            </div>
        </div>
        <!-- Lado Derecho: Formulario de Login -->
        <div class="col-lg-5 d-flex align-items-center justify-content-center bg-white">
            <div class="login-box p-4 p-md-5 w-100" style="max-width: 450px;">
                <div class="mb-5">
                    <h2 class="fw-bold text-dark">Bienvenido de nuevo</h2>
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
                            <a href="#" class="text-primary fw-bold text-decoration-none">Regístrate como ciudadano aquí</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>