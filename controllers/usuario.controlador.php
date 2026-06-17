<?php
    require __DIR__ . '/../models/usuario.php';

    class UsuarioController {
        private $model;

        public function __construct(){
            $this->model = new Usuario();
        }

        public function obtenerUsuarios(){
            return $this->model->findAll();
        }

        public function obtenerUsuario($id){
            return $this->model->findById($id);
        }

        public function crearUsuario(array $data){
            return $this->model->create($data);
        }

        public function editarUsuario($id, array $data){
            return $this->model->update($id, $data);
        }

        public function eliminarUsuario($id){
            return $this->model->delete($id);
        }
    }
?>
