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
        $db = getDatabase();

        // Verificar que existe el usuario
        $usuarios = $db->query(
            "SELECT rut FROM usuario WHERE rut = ?",
            [$rut_usuario]
        );

        if (count($usuarios) === 0) {

            $primerUser = $db->query(
                "SELECT rut FROM usuario LIMIT 1"
            );

            if (!empty($primerUser)) {
                $rut_usuario = $primerUser[0]['rut'];
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No hay usuarios registrados.'
                ]);
                exit;
            }
        }
        
        // Obtener el nuevo id_comentario manualmente si no es auto_increment
        $resultadoMax = $db->query("SELECT MAX(id_comentario) AS max_id FROM comentario");

        $row = $resultadoMax[0] ?? null;
        $nuevo_id = ($row['max_id'] !== null) ? $row['max_id'] + 1 : 1;
        
        // Insertar en comentario
        $db->execute("INSERT INTO comentario (id_comentario, comentario, fecha_comentario, id_publicacion) VALUES (?, ?, ?, ?)",[$nuevo_id, $comentarioTexto, $fecha, $id_publicacion]);
        
        // Insertar en comenta
        $db->execute( "INSERT INTO comenta (rut_usuario, id_comentario) VALUES (?, ?)", [$rut_usuario, $nuevo_id]);

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
