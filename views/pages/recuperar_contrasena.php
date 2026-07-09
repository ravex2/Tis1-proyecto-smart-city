<?php
require_once __DIR__ . '/../../controllers/password_reset.controlador.php';

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new PasswordResetController();
    $email = trim($_POST['email'] ?? '');

    $resultado = $auth->solicitarRecuperacion($email);
    $mensaje = $resultado['message'];
    $tipoMensaje = $resultado['success'] ? 'success' : 'danger';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-3">Recuperar contraseña</h2>
                        <p class="text-muted mb-4">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

                        <?php if ($mensaje): ?>
                            <div class="alert alert-<?= htmlspecialchars($tipoMensaje) ?>">
                                <?= htmlspecialchars($mensaje) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="?ruta=recuperar-contrasena">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.cl" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Enviar enlace de recuperación</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="?ruta=login" class="text-decoration-none">Volver al inicio de sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
