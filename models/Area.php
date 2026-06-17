<?php
    require_once __DIR__ . '/../config/database.php';

    $db = getDatabase();
    $pdo = $db->connection();

    function listarAreas(){
        $db = getDatabase();
        return $db->query("SELECT a.id_area, a.nombre_area, a.descripcion, m.nombre AS nombre_municipalidad FROM area_municipal a JOIN municipalidad m on a.id_municipalidad = m.id_municipalidad");
    }

    function insertarArea(string $nombre,string $descripcion,int $id_municipalidad){
        $db = getDatabase();
        
        return $db->execute("INSERT into area_municipal (nombre_area,descripcion,id_municipalidad) VALUES ('$nombre','$descripcion','$id_municipalidad')");
    }

    function actualizarArea(int $id, string $nombre, string $descripcion, int $id_municipalidad){
        $db = getDatabase();

        return $db->execute("UPDATE area_municipal SET nombre_area='$nombre', descripcion='$descripcion', id_municipalidad='$id_municipalidad' WHERE id_area='$id'");
    }

    function borrarArea(int $id){
        $db = getDatabase();
        return $db->execute("DELETE FROM area_municipal where id_area = ?",[$id]);
    }
?>