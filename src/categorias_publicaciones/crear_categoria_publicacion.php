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
    $nombre = $_POST["nombre"];
    if (!empty($nombre)) {
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