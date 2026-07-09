<?php
    require_once __DIR__ . "/../../config/database.php";
    $db = getDatabase();
    if(isset($_GET["id_enviado"])){
        $id_capturado = $_GET["id_enviado"];
        
        
        $resultado =$db->query(
            "SELECT * FROM categoria_publicacion WHERE id_categoria = ?",
            [$id_capturado]
        );
        $fila = $resultado[0] ?? null;

        if(!$fila){
            header("Location: leer_categoria_publicacion");
            exit();
        }


    }else{
        echo "No existe este ID";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nuevo_nombre = $_POST["nombre"];
        if($nuevo_nombre !=""){
            $resultado = $db->execute(
                "UPDATE categoria_publicacion SET nombre = ? WHERE id_categoria = ?",
                [$nuevo_nombre,$id_capturado]
            );
            
            if($resultado){
                header("Location: leer_categoria_publicacion");
                exit();
            }else{
                echo "Error al actualizar";
            }
        }else{
            echo "El nombre no puede estar vacio";
        }
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
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/panel.css">


</head>
<body>

    <div class="container-fluid">
    <div class="row">

        <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-4">
                <div class="feed-header p-3 sticky-top bg-white-glass blur">
                    <h5 class="fw-bold mb-0">Editar Nombre Categoria Publicacion</h5>
                    
                </div>
                
                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">

                        <form method="POST">
                            <label>Nombre de la Categoria</label>
                            <input type="text" name="nombre" class="form-control rounded-pill px-3 py-2"
                                value="<?php echo $fila['nombre']; ?>" required>

                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">Actualizar</button>
                        </form>

                    </div>
                    
                </div>
                <div class="d-flex justify-content-end">
                    <a href="?ruta=leer_categoria_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ver listado de categorias
                    </a>

                    <a href="?ruta=crear_publicacion" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Volver
                    </a>
                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>



