<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../controllers/autenticacion.controlador.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received";

    $auth = new AuthController();

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $data = [
        'rut' => $_POST['rut'] ?? '',
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'direccion' => $_POST['direccion'] ?? '',
    ];
    
    $user = $auth->registro(trim($email), $password, $confirm_password,$data,true);
    echo $user['message'];
    if ($user['success']) {
        $_SESSION['user'] = $user;
        header('Location: ?ruta=verificacion_correo');
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


?>
<?php
include __DIR__ . "../../layout/header.php";
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
            <div class="login-box p-4 p-md-5 w-100" style="max-width: 600px;">
                <div class="mb-5">
                    <h2 class="fw-bold text-dark">Bienvenido de nuevo Registrate</h2>
                    <p class="text-muted">Ingresa tus credenciales para acceder al portal ciudadano.</p>
                </div>

                <form id="loginForm" method="post" action=""> 
                    <div class="mb-4">
                        <label class="form-label fw-semibold">RUT</label>
                        <div class="input-group custom-input-group shadow-sm">
                            <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="rut" class="form-control border-0 py-3" placeholder="12.345.678-9" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Correo Electrónico</label>
                        <div class="input-group custom-input-group shadow-sm">
                            <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-0 py-3" placeholder="nombre@ejemplo.cl" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Nombre</label>
                                <div class="input-group custom-input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="nombre" class="form-control border-0 py-3" placeholder="Nombre" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Apellido</label>
                                <div class="input-group custom-input-group shadow-sm">
                                    <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="apellido" class="form-control border-0 py-3" placeholder="Apellido" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <div class="input-group custom-input-group shadow-sm">
                            <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-0 py-3" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirmar Contraseña</label>
                        <div class="input-group custom-input-group shadow-sm">
                            <span class="input-group-text border-0 bg-transparent ps-3"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="confirm_password" class="form-control border-0 py-3" placeholder="••••••••" required>
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
include __DIR__ . "../../layout/footer.php";
?>