<?php
    require __DIR__ . '/../models/reacciona.php';

    class ReaccionaController {
        private $model;

        public function __construct(){
            $this->model = new Reacciona();
        }

        public function obtenerReacciones(){
            return $this->model->findAll();
        }

        public function obtenerReaccion($id){
            return $this->model->findById($id);
        }

        public function crearReaccion(array $data){
            return $this->model->create($data);
        }

        public function editarReaccion($id, array $data){
            return $this->model->update($id, $data);
        }

        public function eliminarReaccion($id){
            return $this->model->delete($id);
        }
    }
?>
