<?php
    require_once __DIR__ . "/../../config/database.php";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
  
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
            $rut = $_SESSION['user']['rut'];
            $consulta = "SELECT * FROM reporte WHERE rut_usuario='$rut'";
            $db = getDatabase();
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
                                    <a href='?ruta=ver_reporte&id_enviado=".$fila['id_reporte']."'
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>