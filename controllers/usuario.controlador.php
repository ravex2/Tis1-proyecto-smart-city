<?php
    require __DIR__ . '/../models/usuario.php';
    require __DIR__ . '/../models/funcionario_municipal.php';

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

        public function cambiarRol($rut, $id_rol){
            $resultado = $this->model->update(
                ['rut' => $rut],
                ['id_rol' => $id_rol]
            );
            if (!$resultado) {
                return [
                    'ok' => false,
                    'message' => 'No se pudo actualizar el rol'
                ];
            }
        $id_rol_ciudadano = 1; 

        if ($id_rol != $id_rol_ciudadano) {
            $funcionarioModel = new FuncionarioMunicipal();
            $funcionario = $funcionarioModel->findByRut($rut);

            if (!$funcionario) {
                $funcionarioModel->create([
                    'rut_usuario' => $rut,
                    'id_area_municipal' => 1 // Área por defecto inicial
                ]);
            }
        }

            return [
                'ok' => true,
                'message' => 'Rol asignado correctamente'
            ];
        }

        public function eliminarUsuario(string $rut){
            return $this->model->delete(['rut' => $rut]);
        }
        public static function tienePermiso(string $permisoRequerido): bool {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user']['permisos'])) {
                return in_array($permisoRequerido, $_SESSION['user']['permisos']);
            }
            $id_rol = $_SESSION['user']['id_rol'] ?? null;
            if (!$id_rol) {
                return false;
            }

            $model = new Usuario();
            $permisos = $model->obtenerPermisosPorRol($id_rol);

            $_SESSION['user']['permisos'] = $permisos;

            return in_array($permisoRequerido, $permisos);
        }
    }
?>
