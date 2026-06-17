<?php
require_once __DIR__ . "/../../config/database.php";

$consulta = "SELECT * FROM publicacion WHERE tipo_estado = 'activa'
ORDER BY fecha DESC";
$resultado = $conexion->query($consulta);
?>

<h2>Feed Publicaciones</h2>

<?php
foreach ($resultado as $fila) {
    if($fila['fecha_evento']){
        $fecha_sin_hora = date("d-m-Y",strtotime($fila['fecha_evento']));
    }else{
        $fecha_sin_hora = "Sin fecha";
    }



    echo "<div>";
    
    echo "<h3>".$fila['titulo']."</h3>";
    echo "<img src='../src/publicaciones/".$fila['imagen']."' width='100'></td>";
    echo "<p>".$fila['contenido']."</p>";
    echo "<p>".$fila['lugar']. " Fecha:".$fecha_sin_hora."</p>";

    echo "</div>";
}
?>