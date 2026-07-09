<?php
require_once __DIR__ . '/../../controllers/password_reset.controlador.php';

$mensaje = '';
$tipoMensaje = '';
$token = $_GET['token'] ?? '';
$tokenValido = false;

$auth = new PasswordResetController();

if ($token) {
    $registro = $auth->validarToken($token);
    $tokenValido = !empty($registro);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $nuevaContrasena = $_POST['password'] ?? '';
    $confirmarContrasena = $_POST['confirm_password'] ?? '';

    $resultado = $auth->restablecerContrasena($token, $nuevaContrasena, $confirmarContrasena);
    $mensaje = $resultado['message'];
    $tipoMensaje = $resultado['success'] ? 'success' : 'danger';
    if ($resultado['success']) {
        $tokenValido = false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-3">Restablecer contraseña</h2>
                        <?php if ($mensaje): ?>
                            <div class="alert alert-<?= htmlspecialchars($tipoMensaje) ?>">
                                <?= htmlspecialchars($mensaje) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($tokenValido): ?>
                            <form method="post" action="?ruta=restablecer-contrasena">
                                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Nueva contraseña</label>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Repite la nueva contraseña</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2">Actualizar contraseña</button>
                            </form>
                        <?php else: ?>
                            <p class="text-muted">El enlace de recuperación no es válido o ha expirado.</p>
                            <a href="?ruta=recuperar-contrasena" class="btn btn-secondary w-100 py-2">Solicitar un nuevo enlace</a>
                        <?php endif; ?>

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
