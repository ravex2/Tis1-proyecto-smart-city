<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

$id_publicacion = $_GET['id_publicacion'] ?? '';

if (empty($id_publicacion)) {
    echo json_encode([]);
    exit;
}

try {
    $db = getDatabase();
    $comentarios = $db->query(
    "SELECT c.id_comentario,
            c.comentario,
            c.fecha_comentario,
            com.rut_usuario
     FROM comentario c
     LEFT JOIN comenta com
        ON c.id_comentario = com.id_comentario
     WHERE c.id_publicacion = ?
     ORDER BY c.fecha_comentario ASC",
    [$id_publicacion]
    );

    echo json_encode($comentarios);
} catch (Exception $e) {
    echo json_encode([]);
}
