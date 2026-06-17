<?php
require_once __DIR__ . "/../../config/database.php";

$consulta = "SELECT * FROM publicacion ORDER BY fecha DESC";
$resultado = $conexion->query($consulta);
?>

<h2>Publicaciones</h2>

<?php
foreach ($resultado as $fila) {
    echo "<div>";
    
    echo "<h3>".$fila['titulo']."</h3>";
    echo "<p>".$fila['contenido']."</p>";
    echo "<small>".$fila['fecha']."</small>";

    echo "</div>";
}
?>