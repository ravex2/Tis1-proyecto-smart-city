<?php
    require_once __DIR__ . "/../../config/database.php";

    $consulta = "SELECT p.*, c.nombre AS categoria_nombre FROM publicacion p
    JOIN categoria_publicacion c ON p.id_categoria = c.id_categoria ORDER BY fecha DESC"; ;
    $db = getDatabase();
    $resultado =$db->query($consulta);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity- Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="">


    <style>

        :root {
            --primary-blue: #3d71ff;
            --bg-main: #f8fafc;
            --border-color: #f1f5f9;
            --shadow-primary: 0 4px 14px rgba(61, 113, 255, 0.3);
        }

        body {
            background-color: var(--bg-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
        }

        .custom-container {
            max-width: 1200px;
        }

        /* Sidebar Navigation */
        .nav-link {
            color: #1a1d23;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 100px;
            padding: 10px 20px;
            transition: background 0.2s;
            width: fit-content;
        }

        .nav-link i {
            font-size: 1.4rem;
        }

        .nav-link:hover {
            background-color: #eef2ff;
            color: var(--primary-blue);
        }

        .nav-link.active {
            font-weight: 700;
            color: var(--primary-blue);
        }

        /* Central Feed */
        .bg-white-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        .feed-header {
            z-index: 100;
            border-bottom: 1px solid var(--border-color);
        }

        .post {
            transition: background 0.2s;
            cursor: pointer;
        }

        .post:hover {
            background-color: #f9fafb;
        }

        textarea:focus {
            box-shadow: none !important;
        }

        .post-icons i {
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: 0.2s;
        }

        .post-icons i:hover {
            background-color: #eef2ff;
        }

        /* Right Side Trends */
        .trend-card {
            border: 1px solid #edf2f7;
        }

        .hover-effect:hover {
            background-color: #edf2f7;
            cursor: pointer;
        }

        .user-profile-btn {
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-profile-btn:hover {
            background-color: #edf2f7;
        }

        .shadow-primary {
            box-shadow: var(--shadow-primary);
        }

        .tiny {
            font-size: 0.7rem;
        }

        /* Action Items Hover Colors */
        .action-item:hover { color: var(--primary-blue); }
        .text-danger-hover:hover { color: #ef4444 !important; }

    </style>

</head>
<body>

    <div class="container-fluid">
        <div class="row gx-4">
            <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>
    

            <main class="col-md-10 border-start border-end px-0 bg-white shadow-sm min-vh-100">
                <div class="feed-header p-3 sticky-top bg-white-glass blur">
                    <h5 class="fw-bold mb-0">Listado Publicaciones</h5>
                </div>
                
            <?php if(count($resultado) > 0){ ?>
            <?php   foreach($resultado as $fila){?>
                            <div class="card mb-4 shadow-sm border-0 rounded-4">

                                <div class="card-body">

                                    <h4 class="fw-bold"><?php echo $fila['titulo']; ?></h4>

                                    <span class="badge bg-primary mb-2">
                                        <?php echo $fila['categoria_nombre']; ?>
                                    </span>

                                    <div class="mb-3">
                                        <img src="../src/publicaciones/<?php echo $fila['imagen']; ?>" 
                                            class="img-fluid rounded-3" style="max-height:300px; object-fit:cover;">
                                    </div>

                                    <p class="text-muted"><?php echo $fila['contenido']; ?></p>

                                    <p class="small text-secondary mb-2">
                                        <?php echo $fila['lugar']; ?> |
                                        <?php echo date("d-m-Y", strtotime($fila['fecha_evento'])); ?>
                                    </p>

                                    <div class="d-flex gap-2">

                                        <a href="?ruta=editar_publicacion&id_enviado=<?php echo $fila['id_publicacion']; ?>" 
                                        class="btn btn-primary rounded-pill px-4 fw-bold shadow-primary">
                                            Editar
                                        </a>

                                        <a href="?ruta=eliminar_publicacion&id_enviado=<?php echo $fila['id_publicacion']; ?>" 
                                        class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-primary">
                                            Eliminar
                                        </a>

                                    </div>

                                </div>

                            </div>

                <?php  }?>
            <?php }?>
                
                
            </main>
        </div>
    </div>

    
    
</body>
</html>
