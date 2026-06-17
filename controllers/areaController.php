<?php

    require __DIR__ . '/../models/Area.php';

    class areaController {


        public function obtenerArea(){
            return listarAreas();
        }

        public function crearArea(string $nombre, string $descripcion, int $id_municipalidad) {
            return insertarArea($nombre,$descripcion, $id_municipalidad);
        }
        public function editarArea(int $id, string $nombre, string $descripcion, int $id_municipalidad){
            return actualizarArea($id, $nombre, $descripcion, $id_municipalidad);
        }

        public function eliminarArea(int $id){
            return borrarArea($id);
        }

    }

?>