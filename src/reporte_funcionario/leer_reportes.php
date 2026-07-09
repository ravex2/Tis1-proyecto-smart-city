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
                        <div class="flex-grow-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Fecha Reporte</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Leer Reporte</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $consulta = "SELECT * FROM reporte ORDER BY fecha DESC";
                                        $resultado =$db->query($consulta);

                                        if(count($resultado) > 0){
                                            foreach($resultado as $fila){
                                                $fecha =$fila['fecha'];
                                                $tipo_estado =$fila['tipo_estado'];
                                                $descripcion =$fila['descripcion'];
                                                echo "<tr>";
                                                    echo "<td>".$fecha."</td>";                
                                                    echo "<td>".$tipo_estado."</td>";
                                                    echo "<td>".$descripcion."</td>";
                                                    echo    "<td>
                                                                <a href='?ruta=ver_reporte_funcionario&id_enviado=".$fila['id_reporte']."'
                                                                class='btn btn-primary rounded-pill px-4 fw-bold shadow-primary'>
                                                                    Ver seguimiento
                                                                </a>
                                                            </td>";
                                                echo "</tr>";
                                            }
                                        }else{
                                            echo    "<tr>
                                                        <td>No hay Reportes</td>
                                                    </tr>";
                                        }
                                        
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </main>

            
        </div>
    </div>
    
</body>
</html>
