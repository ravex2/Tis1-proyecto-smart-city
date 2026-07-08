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
            $rolesMunicipales = [2, 3]; // Funcionario, Administrador

            if (in_array($id_rol, $rolesMunicipales)) {

                $funcionarioModel = new FuncionarioMunicipal();

                $funcionario = $funcionarioModel->findByRut($rut);

                if (!$funcionario) {
                    $funcionarioModel->create([
                        'rut_usuario' => $rut,
                        'id_area_municipal' => 1]);
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
        
    }
?>
