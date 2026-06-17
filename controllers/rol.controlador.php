<?php
    require __DIR__ . '/../models/rol.php';

    class RolController {
        private $model;

        public function __construct(){
            $this->model = new Rol();
        }

        public function obtenerRoles(){
            return $this->model->findAll();
        }

        public function obtenerRol($id){
            return $this->model->findById($id);
        }

        public function crearRol(array $data){
            return $this->model->create($data);
        }

        public function editarRol($id, array $data){
            return $this->model->update($id, $data);
        }

        public function eliminarRol($id){
            return $this->model->delete($id);
        }
    }
?>
