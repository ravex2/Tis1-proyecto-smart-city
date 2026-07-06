<?php
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    if (!empty($nombre)) {
        $id_funcionario = 1;
        $db = getDatabase();
        $resultado = $db->execute(
            "INSERT INTO categoria_publicacion (nombre, id_funcionario) VALUES(?,?)",
            [$nombre, $id_funcionario]
        );

        if ($resultado) {
            header("Location: ?ruta=leer_categoria_publicacion");
            exit();
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
    <input type="text" name="nombre" placeholder="Nombre categoraa" required>
    <button type="submit">Guardar</button>
</form>