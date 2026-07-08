<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id_comentario = $data['id_comentario'] ?? '';
    $comentarioTexto = $data['comentario'] ?? '';

    if (empty($id_comentario) || empty($comentarioTexto)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
        exit;
    }

    try {
        $db = getDatabase();

        $db->execute(
            "UPDATE comentario SET comentario = ? WHERE id_comentario = ?",
            [$comentarioTexto, $id_comentario]
        );

        echo json_encode([
            'success' => true,
            'message' => 'Comentario actualizado con éxito.'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
