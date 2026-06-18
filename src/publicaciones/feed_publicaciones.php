<?php
require_once __DIR__ . "/../../config/database.php";

$consulta = "SELECT * FROM publicacion WHERE tipo_estado = 'activa'
ORDER BY fecha DESC";
$resultado = $conexion->query($consulta);

if (isset($_GET['voto_pub']) && isset($_GET['tipo_voto'])) {
    $id_p = intval($_GET['voto_pub']);
    $tipo_r = $_GET['tipo_voto'];
    
    // 1. Buscamos si ESTA publicación específica ya tiene alguna reacción registrada
    $stmt_check = $conexion->prepare("SELECT id_reaccion, tipo_reaccion FROM reaccion WHERE id_publicacion = ?");
    $stmt_check->execute([$id_p]);
    $reaccion_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if ($reaccion_existente) {
        if ($reaccion_existente['tipo_reaccion'] === $tipo_r) {
            // CASO 3 (DELETE): Se presionó la misma -> Se borra usando su ID único real
            $stmt_delete = $conexion->prepare("DELETE FROM reaccion WHERE id_reaccion = ?");
            $stmt_delete->execute([$reaccion_existente['id_reaccion']]);
        } else {
            // CASO 2 (UPDATE): Cambió de opinión -> Se actualiza esa fila específica
            $stmt_update = $conexion->prepare("UPDATE reaccion SET tipo_reaccion = ? WHERE id_reaccion = ?");
            $stmt_update->execute([$tipo_r, $reaccion_existente['id_reaccion']]);
        }
    } else {
        // CASO 1 (INSERT CORREGIDO): No había voto. No forzamos el ID '1'.
        // Dejamos que MySQL le asigne su número correlativo automático (1, 2, 3...)
        $stmt_insert = $conexion->prepare("INSERT INTO reaccion (id_publicacion, tipo_reaccion) VALUES (?, ?)");
        $stmt_insert->execute([$id_p, $tipo_r]);
    }
    
    // Redirección manteniendo la posición de la pantalla
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?#post-" . $id_p);
    exit;
}











?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity - Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="social-style.css">

    <style>

        /* Detalles de la publicación principal */
        .post-main-text {
            color: #0f172a;
            line-height: 1.4;
            word-wrap: break-word;
        }

        /* Iconos con hover de color */
        .action-hover-blue:hover { color: #3d71ff; cursor: pointer; }
        .action-hover-green:hover { color: #10b981; cursor: pointer; }
        .action-hover-red:hover { color: #ef4444; cursor: pointer; }

        /* Hilos de comentarios */
        .comment-item {
            transition: background 0.2s;
        }

        .comment-item:hover {
            background-color: rgba(0,0,0,0.01);
        }

        .thread-line {
            position: absolute;
            left: 35px; /* Ajustar según el centro del avatar */
            top: 60px;
            bottom: 0;
            width: 2px;
            background-color: #f1f5f9;
        }

        .bg-light-soft {
            background-color: #f8fafc;
        }

        /* Botón de volver pequeño */
        .btn-icon-soft.sm {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
            box-shadow: none;
            border: none;
        }

        .btn-icon-soft.sm:hover {
            background-color: #f1f5f9;
        }

    </style>
</head>
<body>

<div class="container custom-container">
    <div class="row gx-4">
        
        <aside class="col-md-3 d-none d-md-flex flex-column py-4 sticky-top vh-100">
            <div class="logo-area mb-4 px-3">
                <i class="bi bi-intersect fs-2 text-primary"></i>
            </div>
            <nav class="nav flex-column gap-2 mb-auto">
                <a class="nav-link" href="feed.html"><i class="bi bi-house-door me-3"></i> Inicio</a>
                <a class="nav-link active" href="#"><i class="bi bi-hash me-3"></i> Explorar</a>
                <a class="nav-link" href="#"><i class="bi bi-bell me-3"></i> Notificaciones</a>
                <a class="nav-link" href="#"><i class="bi bi-person me-3"></i> Perfil</a>
            </nav>
        </aside>

        
        <main class="col-md-6 border-start border-end px-0 bg-white shadow-sm min-vh-100">
            <div class="feed-header p-3 sticky-top bg-white-glass blur d-flex align-items-center gap-4">
                <a href="feed.html" class="btn-icon-soft sm"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Publicaciones</h5>
            </div>
        <?php if($resultado->rowCount()>0){ ?>
        <?php   foreach($resultado as $fila){
            $id_pub = $fila['id_publicacion'];
            $total_me_guta    = $conexion->query("SELECT COUNT(*) FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'me gusta'")->fetchColumn();
            $total_me_encanta = $conexion->query("SELECT COUNT(*) FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'me encanta'")->fetchColumn();
            $total_no_me_gusta = $conexion->query("SELECT COUNT(*) FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'no me gusta'")->fetchColumn();
            $total_me_divierte   = $conexion->query("SELECT COUNT(*) FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'me divierte'")->fetchColumn();
        
            
            ?>
            <div class="post-detail-content p-4 border-bottom">
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <img  class="rounded-circle shadow-sm" width="56" height="56">
                        <div>
                            <div class="post-main-text fs-4 fw-medium mb-3 lh-sm">
                                <?php echo $fila['titulo']; ?>
                            
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="post-main-text fs-4 fw-medium mb-3 lh-sm">
                                    <img src="../src/publicaciones/<?php echo $fila['imagen']; ?>" 
                                    class="img-fluid rounded-3" style="max-height:300px; object-fit:cover;">
                </div>
                <div class="fw-bold fs-5 mb-0">
                    <?php echo $fila['contenido']; ?>
                </div>
                

                <div class="text-muted small mb-3">
                    <span class="fw-bold text-dark">Lugar:</span> <?php echo $fila['lugar']; ?> |
                    <span class="fw-bold text-dark">Fecha:</span>
                    <?php 
                        echo $fila['fecha_evento'] 
                        ? date("d-m-Y", strtotime($fila['fecha_evento'])) 
                        : "Sin fecha";
                    ?>
                </div>

                <hr class="opacity-10">

                
                <div class="d-flex gap-4 py-1 border-bottom border-top border-light my-3 py-3">
                    <div>
                        <span class="fw-bold">0</span> <span class="text-muted">Comentarios</span>
                    </div> 
                    <div>
                        <span class="fw-bold"><?php echo $total_me_guta; ?></span> <span class="text-muted">Me gusta</span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $total_me_encanta; ?></span> <span class="text-muted">Me encanta</span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $total_no_me_gusta; ?></span> <span class="text-muted">No me gusta</span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $total_me_divierte; ?></span> <span class="text-muted">Me divierte</span>
                    </div>


                </div>

                <!-- Botones de Acción Grandes -->
                <div class="d-flex justify-content-around text-muted fs-5 py-1">
                    <i class="bi bi-chat action-hover-blue"></i>            <!-- Comentarios -->

                    <a href="?voto_pub=<?php echo $id_pub; ?>&tipo_voto=me gusta" class="text-muted action-hover-green text-decoration-none">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </a> <!-- Me gusta -->
                    <a href="?voto_pub=<?php echo $id_pub; ?>&tipo_voto=me encanta" class="text-muted action-hover-blue text-decoration-none">
                        <i class="bi bi-heart"></i>
                    </a> <!-- Me encanta -->
                    <a href="?voto_pub=<?php echo $id_pub; ?>&tipo_voto=no me gusta" class="text-muted action-hover-red text-decoration-none">
                        <i class="bi bi-hand-thumbs-down"></i>
                    </a> <!-- No me gusta -->
                    <a href="?voto_pub=<?php echo $id_pub; ?>&tipo_voto=me divierte" class="text-muted action-hover-green text-decoration-none">
                        <i class="bi bi-emoji-grin"></i>
                    </a>     <!-- Me divierte -->
                    
                </div>
            </div>
            
            <div class="comment-input-area p-3 border-bottom bg-light-soft">
                <div class="d-flex gap-3">
                    <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle" width="40" height="40">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control border-0 bg-transparent py-2" placeholder="Publica tu respuesta">
                    </div>
                    <button class="btn btn-primary rounded-pill px-4 btn-sm fw-bold shadow-primary">Responder</button>
                </div>
            </div>
            <?php  }?>
            <?php }?>
            
        </main>
            
    </div>
</div>

</body>
</html>