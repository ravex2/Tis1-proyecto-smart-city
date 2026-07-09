<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../controllers/autenticacion.controlador.php';

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
if ($ruta == "editar_publicacion") {
    include __DIR__ . "/../src/publicaciones/editar_publicacion.php";
    return;
}

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auth = new AuthController();

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = $auth->login(trim($login), $password);

    if ($user) {
        $_SESSION['user'] = $user;

        if ($_SESSION['user']['tipo_interfaz'] === 'interno') {
            // Envía al Administrador y a los funcionarios a su panel de gestión
            header('Location: ?ruta=dashboard');
            exit();
        } else {
            // Envía a los Ciudadanos y Emprendedores al feed comunitario
            header('Location: ?ruta=publicaciones');
            exit();
        }

    } else {
        $errorMessage = 'Credenciales incorrectas. Por favor, inténtalo de nuevo.';
    }
}


include __DIR__ . "/layout/header.php";
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
    <link rel="stylesheet" href="assets/css/login.css">

    <style>
        :root {
            --primary-blue: #3d71ff;
            --bg-light: #ffffff;
        }

        .left-side-container {
            background-color: #ffffff;
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
            background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(13, 30, 76, 0.2)), 
                        url('https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
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
            color: white;
        }

        .diagonal-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            box-shadow: inset -20px 0 30px -20px rgba(0,0,0,0.3);
        }
    </style>
</head>

<body>

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
                    <h2 class="fw-bold text-dark">Bienvenido de nuevo</h2>
                    <p class="text-muted">Ingresa tus credenciales para acceder al portal ciudadano.</p>
                </div>

                <form method="post" action="">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" name="login" class="form-control py-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input type="password" name="password" class="form-control py-3" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                        Iniciar Sesión
                    </button>

                    <button id="google-login-btn" class="googlebtn mt-2 mx-auto">
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                            <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                            <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                            <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                        </svg>
                        Iniciar sesión con Google
                    </button>

            
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger mt-3">
                            <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-5">
                        <p class="text-muted small">
                            ¿No tienes una cuenta aún? <br>
                            <a href="?ruta=registro" class="text-primary fw-bold text-decoration-none">
                                Regístrate como ciudadano aquí
                            </a>
                        </p>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . "/layout/footer.php"; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const googleBtn = document.getElementById('google-login-btn');
        
        if (googleBtn) {
            googleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                loginWithGoogle(this);
            });
        }
    });

    function loginWithGoogle(button) {
        console.log("Login with Google button clicked");
        
        if (!navigator.onLine) {
            alert('No hay conexión a internet. Por favor, verifica tu conexión.');
            return;
        }
        
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner"></span> Conectando con Google...';
        
        try {
            setTimeout(() => {
                window.location.href = '?ruta=auth/google';
            }, 300);
        } catch (error) {
            console.error('Error al redirigir:', error);
            alert('Error al iniciar el proceso de autenticación. Por favor, intenta nuevamente.');
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    }


</script>
</body>
</html>