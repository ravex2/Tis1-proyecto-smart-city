<?php
    require_once __DIR__ . "/../../config/database.php";
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $db = getDatabase();
    $usuarioLogeado = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Comercios Locales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/panel.css">
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
                
                <div class="border rounded shadow-sm bg-white p-3">
                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nombre Categoria</th>
                                <th scope="col">Id Funcionario</th>
                                <th scope="col">Opciones</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $consulta = "SELECT cp.id_categoria , cp.nombre, u.nombre as nombre_p,u.apellido as apellido_p FROM categoria_publicacion cp JOIN funcionario_municipal f ON cp.id_funcionario = f.id_funcionario
                                    JOIN usuario u ON f.rut_usuario = u.rut ";
                                    $db = getDatabase();
                                    $resultado =$db->query($consulta);

                                    if(count($resultado) > 0){
                                        foreach($resultado as $fila){
                                            $id_categoria =$fila['id_categoria'];
                                            $nombre =$fila['nombre'];
                                            $nombre_p =$fila['nombre_p'];
                                            $apellido_p =$fila['apellido_p'];
                                            echo "<tr>";
                                                echo "<td>".$id_categoria."</td>";                
                                                echo "<td>".$nombre."</td>";
                                                echo "<td>".$nombre_p." ".$apellido_p."</td>";
                                                echo "<td><a class='btn btn-primary rounded-pill px-5 fw-bold shadow-primary'  href='?ruta=editar_categoria_publicacion&id_enviado=".$id_categoria."'>Editar </a>";
                                                echo "<a class='btn btn-primary rounded-pill px-5 fw-bold shadow-primary' href='?ruta=eliminar_categoria_publicacion&id_enviado=".$id_categoria."'>Eliminar </a></td>";
                                            echo "</tr>";
                                        }
                                    }else{
                                        echo    "<tr>
                                                    <td>No hay categorias</td>
                                                </tr>";
                                    }
                                    
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                <div class="d-flex justify-content-end">
                    
                    <a href="?ruta=crear_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Volver a Publicacion
                    </a>
                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>


