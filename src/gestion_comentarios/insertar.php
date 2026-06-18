<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/comentario.php';
require_once __DIR__ . '/../../models/comenta.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $comentarioTexto = $data['comentario'] ?? '';
    $id_publicacion = $data['id_publicacion'] ?? '';
    $rut_usuario = $data['rut_usuario'] ?? '12345678-9'; // Poner un valor por defecto para probar si no hay sesión

    if (empty($comentarioTexto) || empty($id_publicacion)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
        exit;
    }

    try {
        $fecha = date('Y-m-d H:i:s');
        
        // Verificar que el rut_usuario existe, sino usar el primero disponible
        $stmtUser = $conexion->prepare("SELECT rut FROM usuario WHERE rut = ?");
        $stmtUser->execute([$rut_usuario]);
        if ($stmtUser->rowCount() === 0) {
            $stmtPrimerUser = $conexion->query("SELECT rut FROM usuario LIMIT 1");
            if ($stmtPrimerUser->rowCount() > 0) {
                $rut_usuario = $stmtPrimerUser->fetchColumn();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error: No hay usuarios en la base de datos para asignar al comentario.']);
                exit;
            }
        }
        
        // Obtener el nuevo id_comentario manualmente si no es auto_increment
        $stmt = $conexion->query("SELECT MAX(id_comentario) as max_id FROM comentario");
        $row = $stmt->fetch();
        $nuevo_id = ($row['max_id'] !== null) ? $row['max_id'] + 1 : 1;
        
        // Insertar en comentario
        $stmtInsert = $conexion->prepare("INSERT INTO comentario (id_comentario, comentario, fecha_comentario, id_publicacion) VALUES (?, ?, ?, ?)");
        $stmtInsert->execute([$nuevo_id, $comentarioTexto, $fecha, $id_publicacion]);
        
        // Insertar en comenta
        $stmtComenta = $conexion->prepare("INSERT INTO comenta (rut_usuario, id_comentario) VALUES (?, ?)");
        $stmtComenta->execute([$rut_usuario, $nuevo_id]);

        echo json_encode([
            'success' => true, 
            'id_comentario' => $nuevo_id,
            'fecha_comentario' => $fecha,
            'comentario' => $comentarioTexto,
            'rut_usuario' => $rut_usuario
        ]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
