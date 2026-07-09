<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);

require_once __DIR__ . "/../../config/database.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se especificó el ID del reporte.");
}

$id_reporte = $_GET['id'];
$db = getDatabase();

$consulta = "SELECT r.id_reporte, r.titulo, r.descripcion, r.tipo_estado, 
                    r.fecha, r.latitud, r.longitud, r.rut_usuario, c.nombre_categoria 
             FROM reporte r
             LEFT JOIN categoria_reporte c ON r.id_categoria_reporte = c.id_categoria
             WHERE r.id_reporte = ? ";
$resultado = $db->query($consulta, [$id_reporte]);

$reporte = null;
if (is_array($resultado)) {
    if (isset($resultado['id_reporte'])) {
        $reporte = $resultado;
    } elseif (isset($resultado[0])) {
        $reporte = $resultado[0];
    }
}

if (!$reporte) {
    die("Error: El reporte solicitado no existe o no contiene datos.");
}

// Configuración de cabeceras para Excel nativo antiguo (evita alertas y arregla formatos)
header("Content-Type: application/vnd.ms-excel; charset=windows-1252");
header("Content-Disposition: attachment; filename=Reporte_Fila_" . $id_reporte . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Función auxiliar para limpiar caracteres especiales convirtiéndolos a codificación de Windows
function toWin($texto) {
    return mb_convert_encoding($texto ?? '', 'Windows-1252', 'UTF-8');
}
?>
<table border="1" style="font-family: Arial, sans-serif; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #3d71ff; color: white; font-weight: bold; text-align: center;">
            <th colspan="2" style="padding: 10px; font-size: 14px;"><?php echo toWin("FICHA TÉCNICA DEL REPORTE INDIVIDUAL"); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold; width: 180px;">ID Reporte</td>
            <td><?php echo $reporte['id_reporte']; ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold;"><?php echo toWin("Título del Incidente"); ?></td>
            <td><?php echo toWin(htmlspecialchars($reporte['titulo'])); ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold;"><?php echo toWin("Categoría asignada"); ?></td>
            <td><?php echo toWin(htmlspecialchars($reporte['nombre_categoria'] ?? 'Sin Categoría')); ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold;">Estado Actual</td>
            <td><?php echo toWin(htmlspecialchars(ucfirst($reporte['tipo_estado']))); ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold;">Fecha Reportada</td>
            <td><?php echo $reporte['fecha'] ? date("d/m/Y H:i", strtotime($reporte['fecha'])) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold;">RUT Vecino Informante</td>
            <td><?php echo htmlspecialchars($reporte['rut_usuario']); ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold;">Coordenadas Geográficas</td>
            <td><?php echo "Lat: " . $reporte['latitud'] . " / Lon: " . $reporte['longitud']; ?></td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; font-weight: bold; vertical-align: top;"><div align="left"><?php echo toWin("Descripción Completa"); ?></div></td>
            <td style="text-align: justify; word-wrap: break-word;"><?php echo toWin(htmlspecialchars($reporte['descripcion'])); ?></td>
        </tr>
    </tbody>
</table>
