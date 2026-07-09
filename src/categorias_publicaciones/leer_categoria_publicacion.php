<?php
    require_once __DIR__ . "/../../config/database.php";
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
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/panel.css">


</head>
<body>

    <div class="container-fluid">
    <div class="row">

        <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-4">
                <div class="feed-header p-3 sticky-top bg-white-glass blur">
                    <h5 class="fw-bold mb-0">Listado Categorias</h5>
                    
                </div>
                
                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">

                        <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">id</th>
                                <th scope="col">Nombre Categoria</th>
                                <th scope="col">ID Funcionario</th>
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


