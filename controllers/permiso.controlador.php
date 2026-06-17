<?php
    require __DIR__ . '/../models/permiso.php';

    class PermisoController {
        private $model;

        public function __construct(){
            $this->model = new Permiso();
        }

        public function obtenerPermisos(){
            return $this->model->findAll();
        }

        public function obtenerPermiso($id){
            return $this->model->findById($id);
        }

        public function crearPermiso(array $data){
            return $this->model->create($data);
        }

        public function editarPermiso($id, array $data){
            return $this->model->update($id, $data);
        }

        public function eliminarPermiso($id){
            return $this->model->delete($id);
        }
    }
?>
