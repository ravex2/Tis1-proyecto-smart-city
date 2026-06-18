<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id_comentario = $data['id_comentario'] ?? '';

    if (empty($id_comentario)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
        exit;
    }

    try {
        $db = getDatabase();
        // Primero eliminar la referencia en comenta
        $db->execute(
            "DELETE FROM comenta WHERE id_comentario = ?",
            [$id_comentario]
        );

        // Luego eliminar de comentario
        $db->execute(
            "DELETE FROM comentario WHERE id_comentario = ?",
            [$id_comentario]
        );
        echo json_encode([
            'success' => true,
            'message' => 'Comentario eliminado con éxito.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
