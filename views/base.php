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
    <link rel="stylesheet" href="login-style.css">

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

</body>
</html>