<?php
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_categoria = $_POST["nombre_categoria"];
    if (!empty($nombre_categoria)) {
        $id_funcionario = 1;
        $id_area_municipal = 1;
        $consulta = "INSERT INTO categoria_reporte (nombre_categoria, id_funcionario,id_area_municipal) 
                    VALUES ('$nombre_categoria','$id_funcionario','$id_area_municipal')";
        $db = getDatabase();
        $resultado = $db->query($consulta);

        if ($resultado) {
            echo "Categoria Creada correctamente";
        } else {
            echo "Error al crear";
        }

    } else {
        echo "La categoria nesesita un nombre";
    }
}
?>

<h2>Crear Categoria</h2>

<form method="POST">
    <input type="text" name="nombre_categoria" placeholder="Nombre categoria" required>
    <button type="submit">Guardar</button>
</form>