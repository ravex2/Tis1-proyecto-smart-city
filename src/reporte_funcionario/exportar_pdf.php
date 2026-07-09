<?php
// 1. Desactivar el despliegue de avisos antiguos de PHP en el documento final
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../libs/fpdf/fpdf.php"; 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se especificó el ID del reporte.");
}

$id_reporte = $_GET['id'];
$db = getDatabase();

// 2. Consulta SQL
$consulta = "SELECT r.id_reporte, r.titulo, r.descripcion, r.tipo_estado, 
                    r.fecha, r.latitud, r.longitud, r.rut_usuario, c.nombre_categoria 
             FROM reporte r
             LEFT JOIN categoria_reporte c ON r.id_categoria_reporte = c.id_categoria
             WHERE r.id_reporte = ? ";
$resultado = $db->query($consulta, [$id_reporte]);

// Ajuste clave: Si tu clase de BD devuelve un array de filas, extraemos la primera [0]
$reporte = (is_array($resultado) && isset($resultado[0])) ? $resultado[0] : $resultado;

if (!$reporte || empty($reporte)) {
    die("Error: El reporte solicitado no existe o no contiene datos en la base de datos.");
}

// 3. Inicializar PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);

// Reemplazo de utf8_decode por iconv para compatibilidad con PHP 8.2+
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', "DETALLE DE REPORTE CIUDADANO #" . $reporte['id_reporte']), 0, 1, 'C');
$pdf->Ln(5);

function generarCelda($pdf, $campo, $valor) {
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', $campo), 1, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', $valor ?? 'N/A'), 1, 1, 'L');
}

// 4. Celdas de información
generarCelda($pdf, "Título del Caso:", $reporte['titulo']);
generarCelda($pdf, "Categoría asignada:", $reporte['nombre_categoria'] ?? 'Sin Categoría');
generarCelda($pdf, "Estado Actual:", ucfirst($reporte['tipo_estado']));
generarCelda($pdf, "Fecha de Emisión:", $reporte['fecha'] ? date("d/m/Y H:i", strtotime($reporte['fecha'])) : 'N/A');
generarCelda($pdf, "RUT del Informante:", $reporte['rut_usuario']);
generarCelda($pdf, "Ubicación Geográfica:", "Lat: " . ($reporte['latitud'] ?? 'N/A') . " | Lon: " . ($reporte['longitud'] ?? 'N/A'));

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', "Descripción Detallada del Incidente:"), 1, 1, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 8, iconv('UTF-8', 'windows-1252', $reporte['descripcion'] ?? 'Sin descripción'), 1, 'L');

$pdf->Output('I', 'Reporte_Fila_' . $id_reporte . '.pdf');
?>
