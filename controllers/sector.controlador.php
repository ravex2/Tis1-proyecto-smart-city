<?php
require __DIR__ . '/../models/sector.php';


class SectorControlador {
    private $model;

    public function __construct(){
        $this->model = new Sector();
    }

    public function obtenerSectores(){
        return $this->model->findAll();
    }

    public function obtenerSectorPorId(string $id_sector){
        return $this->model->findById(['id_sector' => $id_sector]);
    }

    public function crearSector(array $data){
        return $this->model->create($data);
    }

    public function editarSector(string $id_sector, array $data){
        return $this->model->update(['id_sector' => $id_sector], $data);
    }

    public function eliminarSector(string $id_sector){
        return $this->model->delete(['id_sector' => $id_sector]);
    }

}

