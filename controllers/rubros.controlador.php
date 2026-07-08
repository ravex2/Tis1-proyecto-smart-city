<?php
require __DIR__ . '/../models/rubro.php';


class RubrosControlador {
    private $model;

    public function __construct(){
        $this->model = new Rubro();
    }

    public function obtenerRubros(){
        return $this->model->findAll();
    }

    public function obtenerRubroPorId(string $id_rubro){
        return $this->model->findById(['id_rubro' => $id_rubro]);
    }

    public function crearRubro(array $data){
        return $this->model->create($data);
    }

    public function editarRubro(string $id_rubro, array $data){
        return $this->model->update(['id_rubro' => $id_rubro], $data);
    }

    public function eliminarRubro(string $id_rubro){
        return $this->model->delete(['id_rubro' => $id_rubro]);
    }

}