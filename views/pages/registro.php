<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/autenticacion.controlador.php';
require_once __DIR__ . "/../../config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $data = [
        'rut' => $_POST['rut'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
        'sector' => $_POST['id_sector'] ?? 1,
    ];
    
    $result = $auth->registro(trim($email), $password, $confirm_password, $data, true);

    if (!empty($result['success'])) {
        // Si el controlador devolvió los datos del usuario, poblar la sesión con ellos
        if (!empty($result['user']) && is_array($result['user'])) {
            $_SESSION['user'] = $result['user'];
        }
        header('Location: ?ruta=verificacion_correo');
        exit();
    }

    // Mostrar mensaje de error cuando no hubo éxito
    if (!empty($result['message'])) {
        echo $result['message'];
        /*
        if ($_SESSION['user']['tipo_interfaz'] === 'interno') {
            // Envía al Administrador y a los funcionarios a su panel de gestión
            header('Location: ?ruta=verificacion_correo');
            exit();
        } else {
            // Envía a los Ciudadanos y Emprendedores al feed comunitario
            //header('Location: ?ruta=registro');
            exit();
        }
        */
    } else {
        $errorMessage = 'Credenciales incorrectas. Por favor, inténtalo de nuevo.';
    }
}

$db = getDatabase();
$sectores = $db->query("SELECT id_sector, nombre FROM sector");

?>

<!doctype html>
<html lang="es">
<head>
    <title>Portal Ciudadano - Registro</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .main-row {
            min-height: 100vh;
        }

        .left-side-container {
            position: relative;
            min-height: inherit;
            background:
                linear-gradient(rgba(255,255,255,.1), rgba(13,30,76,.2)),
                url("https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&q=80&w=1920");
            background-size: cover;
            background-position: center;
            clip-path: polygon(0 0, 95% 0, 65% 100%, 0 100%);
        }

        .overlay-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 10%;
            color: #fff;
        }

        .form-column {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
            background: #fff;
        }

        .login-box {
            width: 100%;
            max-width: 500px;
        }

        .form-control,
        .form-select {
            padding: .6rem !important;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 main-row">
            <div class="col-lg-7 d-none d-lg-block left-side-container">
                <div class="overlay-content">
                    <span class="fs-3 fw-bold">Portal Ciudadano</span>
                    <h1 class="display-4 fw-bold">Tu comuna,<br>más cerca.</h1>
                </div>
            </div>

            <div class="col-lg-5 form-column">
                <div class="login-box">
                    <div class="mb-4">
                        <h2 class="fw-bold text-dark">Regístrate</h2>
                        <p class="text-muted">Ingresa tus datos para acceder al portal ciudadano.</p>
                    </div>

                    <form id="loginForm" method="post" action=""> 
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">RUT</label>
                            <input type="text" name="rut" class="form-control shadow-sm border-0" placeholder="12.345.678-9" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control shadow-sm border-0" placeholder="nombre@ejemplo.cl" required>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold small">Nombre</label>
                                <input type="text" name="nombre" class="form-control shadow-sm border-0" placeholder="Nombre" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-semibold small">Apellido</label>
                                <input type="text" name="apellido" class="form-control shadow-sm border-0" placeholder="Apellido" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Dirección</label>
                            <input type="text" name="direccion" class="form-control shadow-sm border-0" placeholder="Dirección" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Sector</label>
                            <select name="id_sector" class="form-select shadow-sm border-0">
                                <option disabled selected>Seleccione un sector</option>
                                <?php foreach ($sectores as $sector): ?>
                                    <option value="<?= htmlspecialchars($sector['id_sector']) ?>"><?= htmlspecialchars($sector['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Contraseña</label>
                            <input type="password" name="password" class="form-control shadow-sm border-0" placeholder="••••••••" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Confirmar Contraseña</label>
                            <input type="password" name="confirm_password" class="form-control shadow-sm border-0" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            Registrarse
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted small">¿Ya tienes cuenta? <br>
                                <a href="?ruta=login" class="text-primary fw-bold text-decoration-none">Inicia sesión aquí</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
