<?php
    require __DIR__ . '/../models/Area.php';

    class AreaController {
        public function obtenerArea(){
            return listarConFuncionarios();
        }
        public function crearArea(string $nombre, string $descripcion, int $id_municipalidad) {
            return insertarArea($nombre,$descripcion, $id_municipalidad);
        }
        public function editarArea(int $id, string $nombre, string $descripcion, int $id_municipalidad){
            return actualizarArea($id, $nombre, $descripcion, $id_municipalidad);
        }
        function eliminarAreaCompleta(int $id_area) {
            $db = getDatabase();
            $db->execute("DELETE FROM funcionario_municipal WHERE id_area_municipal = ?",[$id_area]);
            return borrarArea($id_area);
        }
    }
?>