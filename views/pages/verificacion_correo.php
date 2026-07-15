<?php
require_once __DIR__ . '/../../controllers/autenticacion.controlador.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mensaje = '';
$tipoMensaje = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $email = $_SESSION['user']['correo'] ?? '';
    $nombre = $_SESSION['user']['nombre'] ?? '';
    $rut = $_SESSION['user']['rut'] ?? '';

    $token = $_COOKIE['session_token'] ?? '';

    echo "$email" . " $nombre" . "$rut" . " $token";

    if ($auth->enviarEmailVerificacion($email, $nombre,$rut, $token)) {
        $mensaje = 'Se ha enviado un email de verificación. Revisa tu bandeja de entrada.';
        $tipoMensaje = 'success';
    } else {
        $mensaje = 'Error al enviar el email de verificación.';
        $tipoMensaje = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Correo - Municipalidad Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-3">Verificación de Correo</h2>
                        <p class="text-muted mb-4">
                            Para acceder al sistema, debes verificar tu correo electrónico.
                        </p>

                        <?php if ($mensaje): ?>
                            <div class="alert alert-<?= $tipoMensaje ?>">
                                <?= htmlspecialchars($mensaje) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="">
                            <p class="text-center mb-4">
                                <strong>Email:</strong> <?= htmlspecialchars($_SESSION['user']['correo'] ?? '') ?>
                            </p>
                            <input type="submit" class="btn btn-primary w-100 py-2" value="Enviar email de verificación">
                        </form>

                        <div class="text-center mt-3">
                            <a href="?ruta=logout" class="text-decoration-none">Cerrar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>