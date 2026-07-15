<?php

    require __DIR__ . '/../models/usuario.php';
    require __DIR__ . '/../models/funcionario_municipal.php';
    require __DIR__ . '/../services/AuthServices.php';

    class UsuarioController {
        private $model;
        private $googleAuth;

        public function __construct(){
            $this->model = new Usuario();
            $this->googleAuth = new GoogleAuthService();
        }

        // ============================================
        // MÉTODOS EXISTENTES
        // ============================================

        public function obtenerUsuarios(){
            return $this->model->findAll();
        }

        public function obtenerUsuario(string $rut){
            return $this->model->findById(['rut' => $rut]);
        }
        public function filtrarCorreo(string $correo): ?array {
            return $this->model->findByCorreo($correo);
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


        // ============================================
        // NUEVOS MÉTODOS PARA GOOGLE AUTH
        // ============================================

        public function redirectToGoogle(): void {
            $authUrl = $this->googleAuth->getAuthUrl();
            header('Location: ' . $authUrl);
            exit;
        }

        public function handleGoogleCallback(): ?array {
            if (!isset($_GET['code'])) {
                return null;
            }

            $googleUser = $this->googleAuth->authenticate($_GET['code']);
            
            if (!$googleUser) {
                return null;
            }

            // Separar nombre y apellido
            $nameParts = explode(' ', $googleUser->name);
            $nombre = $nameParts[0];
            $apellido = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';

            // Buscar usuario por correo
            $user = $this->model->findByCorreo($googleUser->email);

            if ($user) {
                $this->loginUser($user);
                return $user;
            } else {
                $_SESSION['google_temp_user'] = [
                    'google_id' => $googleUser->id,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'correo' => $googleUser->email,
                    'foto' => $googleUser->picture ?? null
                ];

                // Redirigir a formulario de completado
                header('Location: ?ruta=auth/complete-profile');
                exit;
            }
        }

        public function showCompleteProfileForm(): void {
            if (!isset($_SESSION['google_temp_user'])) {
                header('Location: /login');
                exit;
            }
            
            require_once __DIR__ . '/../views/complete_profile.php';
        }

        public function completeProfile(array $data): bool {
            if (!isset($_SESSION['google_temp_user'])) {
                return false;
            }

            $googleData = $_SESSION['google_temp_user'];

            $existingUser = $this->model->findById(['rut' => $data['rut']]);
            if ($existingUser) {
                $_SESSION['error'] = 'El RUT ya está registrado';
                return false;
            }
            $userData = [
                'rut'         => (string) $data['rut'],
                'nombre'      => !empty($googleData['nombre']) ? $googleData['nombre'] : ($data['nombre'] ?? ''),
                'apellido'    => !empty($googleData['apellido']) ? $googleData['apellido'] : ($data['apellido'] ?? ''),
                'correo'      => $googleData['correo'],
                'direccion'   => $data['direccion'] ?? '',
                'contrasenha' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'id_rol'      => 1, // Asignar rol por defecto
                'id_sector'   => $data['id_sector'] ?? 1
            ];

            try {
                // llamar a crearUsuario con los datos combinados
                $this->crearUsuario($userData);
                
                unset($_SESSION['google_temp_user']);      
                
                $userEmailGoogle = $googleData['correo'];
                $user = $this->model->findByCorreo($userEmailGoogle);
                $this->loginUser($user);
                
                return true;
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error al crear el usuario: ' . $e->getMessage();
                return false;
            }
        }

        private function loginUser(array $user): void {
            $_SESSION['user_rut'] = $user['rut'];
            $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
            $_SESSION['user_email'] = $user['correo'];
            $_SESSION['user_photo'] = $user['foto'] ?? null;
            $_SESSION['user_role'] = $user['id_rol'];
            $_SESSION['user_sector'] = $user['id_sector'];
            $_SESSION['logged_in'] = true;
        }

        public function logout(): void {
            session_destroy();
            header('Location: /');
            exit;
        }
    }
?>