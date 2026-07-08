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
            header("Location: ?ruta=leer_categoria_reporte");
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
    <input type="text" name="nombre_categoria" placeholder="Nombre categoria" required>

    <select name="id_area_municipal" required>

        <?php foreach ($areas as $area) { ?>

            <option value="<?php echo $area['id_area']; ?>">
                <?php echo $area['nombre_area']; ?>
            </option>

        <?php } ?>

    </select>


    <button type="submit">Guardar</button>
</form>