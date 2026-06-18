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

        public function obtenerUsuario(string $rut){
            return $this->model->findById(['rut' => $rut]);
        }

        public function crearUsuario(array $data){
            return $this->model->create($data);
        }

        public function editarUsuario(string $rut, array $data){
            return $this->model->update(['rut' => $rut], $data);
        }

        public function eliminarUsuario(string $rut){
            return $this->model->delete(['rut' => $rut]);
        }
    }
?>
