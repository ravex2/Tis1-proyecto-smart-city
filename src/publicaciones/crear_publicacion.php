<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$rut = $_SESSION['user']['rut'];
$consultaFuncionario = "SELECT id_funcionario FROM funcionario_municipal WHERE rut_usuario = ? ";
$resultado = $db->query($consultaFuncionario, [$rut]);
$funcionario = $resultado[0] ?? null;

 $usuarioLogeado = $_SESSION['user'] ?? null;

if(!$funcionario){
    header("Location: ?ruta=dashboard");
    exit();
}

$id_funcionario = $funcionario['id_funcionario'];
$cat_pub = $db->query("SELECT * FROM categoria_publicacion");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $contenido = $_POST["contenido"];
    $titulo = $_POST["titulo"];
    $fecha_evento = $_POST["fecha_evento"] ?? null;
    if ($fecha_evento != "") {
        $fecha_evento = str_replace("T", " ", $fecha_evento) . ":00";
    } else {
        $fecha_evento = null;
    }
    $tipo_estado = $_POST["tipo_estado"];
    $lugar = $_POST["lugar"];
    $imagen = $_POST["imagen"];
    $id_categoria = $_POST["id_categoria"];


    $consulta = "INSERT INTO publicacion(contenido,titulo,fecha_evento,tipo_estado,lugar,imagen, visitas, id_funcionario,id_categoria) 
    VALUES('$contenido','$titulo',".($fecha_evento ? "'$fecha_evento'" : "NULL").",'$tipo_estado','$lugar','$imagen',0,'$id_funcionario',$id_categoria)";
    
    $db->query($consulta);
    header("Location: ?ruta=crear_publicacion");

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/panel.css">


</head>
<body>

    <div class="container-fluid">
    <div class="row">

        <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-4">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">Gestión de Publicaciones</h3>
                        <small class="text-muted">Moderación y administración de publicaciones</small>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dropdown text-end">
                                
                                <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">

                                    <div class="text-start">
                                        <div class="fw-semibold">
                                            <?= $usuarioLogeado['nombre'] . ' ' . $usuarioLogeado['apellido'] ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= $usuarioLogeado['correo'] ?>
                                        </small>
                                    </div>

                                    <div class="me-2">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($usuarioLogeado['nombre'].' '.$usuarioLogeado['apellido']) ?>&background=3d71ff&color=fff&rounded=true&size=40"
                                            class="rounded-circle"
                                            width="40"
                                            height="40"
                                            alt="usuario">
                                    </div>

                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item text-danger" href="?ruta=logout">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                        </a>
                                    </li>

                                </ul>

                            </div>
                        </div>
                    </div>
            </div> 
                <div class="d-flex justify-content-end">
                    <a href="?ruta=crear_categoria_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Crear Nueva Categoria Publicacion
                    </a>
                </div>
                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">
                        <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle" width="48" height="48">
                        <div class="flex-grow-1">




                            <form method="POST" class="mt-2">
                                <div class="mb-3">
                                    <label>Titulo de la Publicacion</label>
                                    <input type="text" name="titulo" class="form-control rounded-pill px-3 py-2"
                                        placeholder="Titulo de la publicacion" required>
                                </div>

                                <div class="mb-3">
                                    <label>Contenido de la publicacion</label>
                                    <textarea name="contenido" class="form-control rounded-4 px-3 py-2"
                                    placeholder="Descripcion de la publicacion" rows="3" required></textarea>
                                </div>

                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <label>Fecha</label>
                                        <input type="datetime-local" name="fecha_evento"
                                        class="form-control rounded-pill px-3 py-2">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Estado Publicacion</label>
                                        <select name="tipo_estado" class="form-select rounded-pill px-3 py-2">
                                            <option value="activa">Activa</option>
                                            <option value="desactivada">No activa</option>
                                        </select>
                                    </div>

                                </div>
        
                                <div class="mb-3">
                                    <label>Lugar</label>
                                    <input type="text" name="lugar" class="form-control rounded-pill px-3 py-2"
                                    placeholder="Lugar del evento" required>
                                </div>

                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <label>Imagen</label>
                                        <input type="text" name="imagen" class="form-control rounded-pill px-3 py-2"
                                            placeholder="imagen_1.jpg" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Categoria</label>
                                        <select name = "id_categoria" class="form-select rounded-pill px-3 py-2">
                                            <?php
                                                foreach($cat_pub as $c){ ?>
                                                    <option value="<?php echo $c['id_categoria']; ?>" >
                                                        <?php echo $c['nombre'];?>
                                                    </option>

                                            <?php } ?>

                                        </select>
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary">
                                        Publicar
                                    </button>
                                </div>
                            </form>
                            
                        </div>

                        
                        
                    </div>
                    
                </div>
                <div class="d-flex justify-content-end">
                    <a href="?ruta=leer_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ir al listado de publicaciones
                    </a>
                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>