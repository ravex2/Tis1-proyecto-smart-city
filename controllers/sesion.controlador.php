<?php
    require __DIR__ . '/../models/sesion.php';

    class SesionController {
        private $model;

        public function __construct(){
            $this->model = new Sesion();
        }

        public function obtenerSesiones(){
            return $this->model->findAll();
        }

        public function obtenerSesion($id){
            return $this->model->findById($id);
        }

        public function crearSesion(array $data){
            return $this->model->create($data);
        }

        public function editarSesion($id, array $data){
            return $this->model->update($id, $data);
        }
        public function editarSesionByRut($rut){
            return $this->model->verificarEmailPorRut($rut);
        }

        public function eliminarSesion($id){
            return $this->model->delete($id);
        }
        public function verificarEmailSesion(string $rut): ?array {
            return $this->model->verificarEmailSesionModel($rut);
        }
    }
?>
