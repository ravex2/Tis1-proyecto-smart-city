<?php
require_once __DIR__ . '/../config/database.php';

class NegocioLocal {
    
    public function insertarEmprendimiento(
        string $nombre, string $rubro, string $sector, string $direccion, 
        string $correo, string $facebook, string $whatsapp, string $instagram, 
        string $dias, string $apertura, string $cierre, string $descripcion, 
        array $imagenes
    ) {
        $db = getDatabase();


        $sql = "INSERT INTO negocio_local (
                    nombre, 
                    id_rubro, 
                    id_sector, 
                    direccion, 
                    correo_electronico, 
                    facebook, 
                    whatsapp, 
                    instagram, 
                    dias_abierto, 
                    hora_apertura, 
                    hora_cierre, 
                    descripcion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        $resultado_negocio = $db->execute($sql, [
            $nombre, 
            $rubro,       
            $sector,     
            $direccion,   
            $correo,     
            $facebook,   
            $whatsapp,   
            $instagram,   
            $dias,        
            $apertura,    
            $cierre,      
            $descripcion  
        ]);

        if ($resultado_negocio) {
            $pdo_nativo = $db->connection();
            $id_negocio = $pdo_nativo->lastInsertId();

            if ($id_negocio && !empty($imagenes['name'][0])) {
                $cantidad = count($imagenes['name']);

                for ($i = 0; $i < $cantidad; $i++) {
                    $nombre_original = $imagenes['name'][$i];
                    $ruta_temporal = $imagenes['tmp_name'][$i];
                    $error = $imagenes['error'][$i];

                    if ($error === 0) {
                        $info = pathinfo($nombre_original);
                        $extension = strtolower($info['extension']);
                        
                        $nombre_unico = time() . "_" . $i . "." . $extension;
                        $carpeta_destino = __DIR__ . '/../public/uploads/' . $nombre_unico;

                        if (move_uploaded_file($ruta_temporal, $carpeta_destino)) {
                            $sql_foto = "INSERT INTO imagenes_negocios (id_negocio, ruta_imagen) VALUES (?, ?)";
                            $db->execute($sql_foto, [$id_negocio, $nombre_unico]);
                        }
                    }
                }
            }
            return true;
        }

        return false;
    }

    public function listarEmprendimientos(){
        $db = getDatabase();

        return $db->query("SELECT n.*, r.nombre_rubro AS rubro, s.nombre AS sector
            FROM negocio_local n
            LEFT JOIN rubro r ON n.id_rubro = r.id_rubro
            LEFT JOIN sector s ON n.id_sector = s.id_sector
            ORDER BY n.id_negocio DESC");
    }

    public function insertarRevision($id_funcionario, $estado, $observacion) {
        $db = getDatabase();
        
        $sqlInsert = "INSERT INTO revision_negocio (tipo_estado, observacion, id_funcionario) 
                    VALUES (?, ?, ?)";
        $estadoLimpio = trim(strtolower($estado));
        $obsFinal = ($estadoLimpio === 'rechazado') ? $observacion : null;
        $db->execute($sqlInsert, [$estadoLimpio, $obsFinal, $id_funcionario]);
        
        $pdo = $db->connection();
        
        return $pdo->lastInsertId();
    }


    public function actualizarEstadoNegocio($id_negocio, $estado, $id_revision) {
        $db = getDatabase();
        $sql = "UPDATE negocio_local SET tipo_estado = ?, id_revision = ? WHERE id_negocio = ?";
        return $db->execute($sql, [$estado, $id_revision, $id_negocio]);
    }

    public function countByEstado(string $estado): int {
        $db = getDatabase();
        $result = $db->query("SELECT COUNT(*) AS total FROM negocio_local WHERE tipo_estado = ?", [$estado]);
        return (int) ($result[0]['total'] ?? 0);
    }
}