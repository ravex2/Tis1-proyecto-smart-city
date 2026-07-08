<?php
require_once __DIR__ . "/../../config/database.php";
$rut = $_SESSION['user']['rut'];
$db = getDatabase();
$cat_pub = $db->query("SELECT * FROM categoria_reporte");

$cat_pub2 = $db->query("SELECT fm.id_funcionario , fm.rut_usuario, u.nombre
                     FROM funcionario_municipal fm JOIN usuario u ON fm.rut_usuario = u.rut ");

    if(isset($_GET["id_enviado"])){

        $id_capturado = $_GET["id_enviado"];

        $consulta = "SELECT * FROM reporte WHERE id_reporte=$id_capturado AND rut_usuario = '$rut'";
        $resultado = $db->query($consulta);
        $fila = $resultado[0] ?? null;

        if(!$fila){
            header("Location: ?ruta=leer_mis_reportes");
            exit();
        }

        $consultaSeguimientos = "SELECT * FROM seguimiento_reporte WHERE id_reporte = $id_capturado ORDER BY fecha ASC";
        $seguimientos = $db->query($consultaSeguimientos);


    }else{
        echo "No existe este ID";
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
                                    <textarea name="descripcion" class="form-control rounded-4 px-3 py-2"
                                    rows="3"  readonly><?php echo $fila['descripcion']; ?></textarea>
                                </div>


                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <input type="text" name="imagen" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $fila['imagen']; ?>"     readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <select name = "id_categoria_reporte" class="form-select rounded-pill px-3 py-2" disabled>
                                            <?php
                                                foreach($cat_pub as $c){ ?>
                                                    <option value="<?php echo $c['id_categoria']; ?>" 
                                                    <?php if($c['id_categoria'] == $fila['id_categoria_reporte']) echo "selected"; ?>
                                                    >
                                                        <?php echo $c['nombre_categoria'];?>
                                                    </option>

                                            <?php } ?>

                                        </select>
                                    </div>

                                </div>
                                <div class="row g-2 mb-3">

                                    <div class="col-md-6">
                                        <input type="text" name="tipo_estado" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $fila['tipo_estado']; ?>"     readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="fecha" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $fila['fecha']; ?>"     readonly>
                                    </div>

                                </div>

                            </form>
                            
                        </div>

                        
                        
                    </div>
                    
                </div>

                <?php
                if (count($seguimientos) > 0) {
                    $contador =1;
                    foreach ($seguimientos as $s) {
                        ?>
                        <div class="post-box p-3 border-bottom">
                            <h1>Seguimiento <?php echo $contador; ?></h1>
                            <div class="flex-grow-1">
                                <div class="mb-3">
                                    <textarea name="observacion" class="form-control rounded-4 px-3 py-2"
                                    rows="3" readonly><?php echo $s['observacion']; ?></textarea>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <input type="text" name="imagen_evidencia" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $s['imagen_evidencia']; ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="fecha" class="form-control rounded-pill px-3 py-2"
                                        value="<?php echo $s['fecha']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-6">
                                        <select name = "id_funcionario" class="form-select rounded-pill px-3 py-2" disabled>
                                            <?php
                                                foreach($cat_pub2 as $c2){ ?>
                                                    <option value="<?php echo $c2['id_funcionario']; ?>" 
                                                    <?php if($c2['id_funcionario'] == $s['id_funcionario']) echo "selected"; ?>
                                                    >
                                                        <?php echo $c2['nombre'];?>
                                                    </option>

                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php
                        $contador++;
                    }

                } else {
                    echo "<p>Aún no existen seguimientos para este reporte.</p>";
                }
                ?>



                


                <div class="d-flex justify-content-end">
                    <a href="?ruta=leer_mis_reportes" class="btn btn-primary rounded-pill px-5 fw-bold shadow-primary">
                        Ir al listado
                    </a>
                </div>
            </main>

            
        </div>
    </div>
</body>
</html>