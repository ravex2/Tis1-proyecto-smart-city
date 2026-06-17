<?php
require __DIR__ . '/../models/categoria_publicacion.php';

class CategoriaPublicacionControlador {
    private $model;
    public function __construct($model) {
        $this->model = new CategoriaPublicacion();
    }

    public function obtenerCategorias(){
        return $this->model->findAll();
    }
    public function obtenerCategoria($id){
        return $this->model->findById($id);
    }
    public function crearCategoria(array $data){
        return $this->model->create($data);
    }
    public function editarCategoria($id, array $data){
        return $this->model->update($id, $data);
    }
    public function eliminarCategoria($id){
        return $this->model->delete($id);
    }
}