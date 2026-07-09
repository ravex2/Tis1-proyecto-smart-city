<?php
require_once __DIR__ . "/../../config/database.php";
$db = getDatabase();
$areas = $db->query("SELECT * FROM area_municipal");

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
    $nombre_categoria = $_POST["nombre_categoria"];
    if (!empty($nombre_categoria)) {
        $id_area_municipal = $_POST["id_area_municipal"];
        

        $resultado = $db->execute(
            "INSERT INTO categoria_reporte (nombre_categoria, id_funcionario,id_area_municipal) VALUES(?,?,?)",
            [$nombre_categoria, $id_funcionario, $id_area_municipal]
        );

        if ($resultado) {
            header("Location: ?ruta=reportes");
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
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/panel.css">


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
                            <div class="mb-3">
                                <label>Nombre de la Categoria</label>
                                <input type="text" name="nombre_categoria" placeholder="Nombre categoria" class="form-control rounded-pill px-3 py-2" required>
                            </div>
                            <div class="mb-3">
                                <label>Area Municipal</label>
                                <select name="id_area_municipal" class="form-select rounded-pill px-3 py-2" required>

                                    <?php foreach ($areas as $area) { ?>

                                        <option value="<?php echo $area['id_area']; ?>">
                                            <?php echo $area['nombre_area']; ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>
                            

                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">Guardar</button>
                        </form>

                    </div>
                    
                </div>
                <div class="d-flex justify-content-end">
                    <a href="?ruta=leer_categoria_reporte" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ver listado de categorias
                    </a>

                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>



