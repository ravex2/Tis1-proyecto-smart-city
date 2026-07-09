<?php
require_once __DIR__ . '/../models/negocio_local.php'; 

class NegocioController {
    private $modeloNegocio;

    public function __construct() {
        // Creamos el objeto del modelo para poder usar sus funciones
        $this->modeloNegocio = new NegocioLocal(); 
    }

    public function crearNegocio(
        string $nombre, 
        string $rubro, 
        string $sector, 
        string $direccion, 
        string $correo, 
        string $facebook, 
        string $whatsapp, 
        string $instagram, 
        string $dias, 
        string $apertura, 
        string $cierre, 
        string $descripcion, 
        array $imagenes
    ) {

        return $this->modeloNegocio->insertarEmprendimiento(
            $nombre,
            $rubro,
            $sector,
            $direccion,
            $correo,
            $facebook,
            $whatsapp,
            $instagram,
            $dias,
            $apertura,
            $cierre,
            $descripcion,
            $imagenes
        );
    }
}
?>