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

if (isset($_GET["id_enviado"])) {
    $id_reporte = $_GET["id_enviado"];

    $consulta = "SELECT * FROM reporte WHERE id_reporte = ? ";
    $resultado = $db->query($consulta,[$id_reporte]);
    $reporte = $resultado[0] ?? null;

    if (!$reporte) {
        header("Location: ?ruta=reportes");
        exit();
    }
}else{
    echo "No existe el reporte.";
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    
    $observacion = $_POST["observacion"];
    $imagen = $_POST["imagen_evidencia"];
    $tipo_estado = $_POST["tipo_estado"];

    $insert = "INSERT INTO seguimiento_reporte (observacion, imagen_evidencia, tipo_estado, id_funcionario, id_reporte)
    VALUES (?,?,? ,? ,? )";


    $db->execute($insert,[
        $observacion,
        $imagen,
        $tipo_estado,
        $id_funcionario,
        $id_reporte
    ]);
    
    $update = "UPDATE reporte SET tipo_estado = ? WHERE id_reporte = ?";


    $db->execute($update,[
        $tipo_estado,
        $id_reporte
    ]);

    header("Location: ?ruta=reportes");
    exit();

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
                    <h5 class="fw-bold mb-0">Inicio</h5>
                    
                </div>
                <div class="post-box p-3 border-bottom"> 
                    <div class="d-flex gap-3">
                        <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle" width="48" height="48">
                        <div class="flex-grow-1">

                            <form method="POST" class="mt-2">
                                <div class="mb-3">
                                    <label>Observacion Reporte</label>
                                    <textarea name="observacion" class="form-control rounded-4 px-3 py-2"
                                    placeholder="Observacion Reporte" rows="3" required></textarea>
                                </div>


                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <label>Imagen</label>
                                        <input type="text" name="imagen_evidencia" class="form-control rounded-pill px-3 py-2"
                                            placeholder="imagen_1.jpg" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Estado del reporte</label>

                                        <select name="tipo_estado" class="form-select" required>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="en proceso">En proceso</option>
                                            <option value="rechazado">Rechazado</option>
                                            <option value="resuelto">Resuelto</option>
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
                    <a href="?ruta=ver_reporte_funcionario&id_enviado=<?php echo $reporte['id_reporte'];?>" 
                    class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Volver al Reporte
                    </a>
                </div>
            </main>

            
        </div>
    </div>
</body>
</html>