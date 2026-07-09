<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../core/database.php';
require_once __DIR__ . '/../../controllers/negocio.controlador.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_negocio  = $_POST['id_negocio'] ?? null;
    $accion      = $_POST['tipo_estado'] ?? null; 
    $observacion = $_POST['observacion'] ?? '';   

    $id_negocio = $id_negocio ? (int)$id_negocio : null;

    $comercio = new NegocioController();
    $comercio->procesarRevision($id_negocio, $accion, $observacion);
    
} else {
    header("Location: ?ruta=comercio");
    exit;
}