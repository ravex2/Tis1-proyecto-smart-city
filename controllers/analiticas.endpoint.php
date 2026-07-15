<?php

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/analiticas.controlador.php';

header('Content-Type: application/json; charset=utf-8');

$controlador = new AnaliticasController();
$rango = $controlador->resolverRangoFechas(
    $_GET['fecha_desde'] ?? null,
    $_GET['fecha_hasta'] ?? null
);

echo json_encode(
    $controlador->obtenerDatosGraficos($rango['desde'], $rango['hasta']),
    JSON_UNESCAPED_UNICODE
);
exit;
