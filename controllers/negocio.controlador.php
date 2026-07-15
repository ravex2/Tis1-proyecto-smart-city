<?php
require_once __DIR__ . '/../models/negocio_local.php'; 
require_once __DIR__ . '/../controllers/usuario.controlador.php';

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
    public function listaNegocios(){
        return $this->modeloNegocio->listarEmprendimientos();
    }
    public function procesarRevision($id_negocio, $accion, $observacion) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $usuario = new UsuarioController();
        if (!$usuario->tienePermiso('control_comercio') && !$usuario->tienePermiso('admin_total')) {
            header("Location: ?ruta=gestion_comercio&status=no_autorizado");
            exit;
        }

        $rut_funcionario = $_SESSION['user']['rut'] ?? null; 
        $db = getDatabase();

        $sqlFuncionario = "SELECT id_funcionario FROM funcionario_municipal WHERE rut_usuario = ?";
        $resultadoFuncionario = $db->query($sqlFuncionario, [$rut_funcionario]);
        
        $id_funcionario = $resultadoFuncionario[0]['id_funcionario'] ?? null;

        if (!$id_funcionario) {
            header("Location: ?ruta=gestion_comercio&status=error_perfil_municipal");
            exit;
        }

        $id_revision_creada = $this->modeloNegocio->insertarRevision($id_funcionario, $accion, $observacion);

        $this->modeloNegocio->actualizarEstadoNegocio($id_negocio, $accion, $id_revision_creada);
        
        header("Location: ?ruta=gestion_comercio&status=ok");
        exit;
    }
}
?>