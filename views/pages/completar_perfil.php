<?php
    require_once __DIR__ . "/../../config/database.php";
    $db = getDatabase();
    $sectores = $db->query("SELECT id_sector, nombre FROM sector");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Perfil - Municipalidad Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h2 class="fw-bold mb-3">¡Bienvenido!</h2>
                        <p class="text-muted mb-4">
                            Es la primera vez que inicias sesión con Google. 
                            Por favor, completa tu información para crear tu cuenta.
                        </p>

                        <?php if (!empty($errorMessage)): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($errorMessage) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="?ruta=auth/complete-profile">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">RUT *</label>
                                <input type="text" name="rut" class="form-control py-2" 
                                       placeholder="12.345.678-9" 
                                       required>
                                <small class="text-muted">Formato: 12.345.678-9</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" name="nombre" class="form-control py-2" 
                                       value="<?= htmlspecialchars($_SESSION['google_temp_user']['nombre'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Apellido</label>
                                <input type="text" name="apellido" class="form-control py-2" 
                                       value="<?= htmlspecialchars($_SESSION['google_temp_user']['apellido'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dirección</label>
                                <input type="text" name="direccion" class="form-control py-2" 
                                       placeholder="Opcional">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sector</label>
                                <select name="id_sector" class="form-select py-2">  
                                    <?php foreach ($sectores as $sector): ?>
                                        <option value="<?= htmlspecialchars($sector['id_sector']) ?>">
                                            <?= htmlspecialchars($sector['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Correo electrónico</label>
                                <input type="email" class="form-control py-2" 
                                       value="<?= htmlspecialchars($_SESSION['google_temp_user']['correo'] ?? '') ?>" 
                                       disabled>
                                <small class="text-muted">Este correo no se puede modificar</small>
                            </div>




                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                Crear Cuenta y Continuar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>