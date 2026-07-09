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
}
?>