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

if(!$funcionario){
    header("Location: ?ruta=dashboard");
    exit();
}

$id_funcionario = $funcionario['id_funcionario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    if (!empty($nombre)) {
        $resultado = $db->execute(
            "INSERT INTO categoria_publicacion (nombre, id_funcionario) VALUES(?,?)",
            [$nombre, $id_funcionario]
        );

        if ($resultado) {
            header("Location: ?ruta=crear_publicacion");
            exit();
        } else {
            echo "Error al crear";
        }

    } else {
        echo "La categoria nesesita un nombre";
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
    <link rel="stylesheet" href="assets/css/panel.css">


</head>
<body>

    <div class="container-fluid">
    <div class="row">

        <?php include BASE_PATH . "/views/layout/sidebar.php"; ?>

        <main class="col-md-10 ms-sm-auto px-4">
                <div class="feed-header p-3 sticky-top bg-white-glass blur">
                    <h5 class="fw-bold mb-0">Crear Categoria</h5>
                    
                </div>
                
                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">

                        <form method="POST">
                            <label>Nombre de la Categoria</label>
                            <input type="text" name="nombre" class="form-control rounded-pill px-3 py-2"
                            placeholder="Nombre de la Categoria" required>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">Guardar</button>
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