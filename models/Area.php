<?php
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/basemodel.php';

    $db = getDatabase();
    $pdo = $db->connection();



    function insertarArea(string $nombre,string $descripcion,int $id_municipalidad){
        $db = getDatabase();
        
        return $db->execute("INSERT into area_municipal (nombre_area,descripcion,id_municipalidad) VALUES ('$nombre','$descripcion','$id_municipalidad')");
    }

    function actualizarArea(int $id, string $nombre, string $descripcion, int $id_municipalidad){
        $db = getDatabase();

        return $db->execute("UPDATE area_municipal SET nombre_area='$nombre', descripcion='$descripcion', id_municipalidad='$id_municipalidad' WHERE id_area='$id'");
    }

    function contarAreas(): int {
        $db = getDatabase();
        $result = $db->query("SELECT COUNT(*) AS total FROM area_municipal");

        return (int) $result[0]['total'];
    }
    function listarConFuncionarios(): array{
        $db = getDatabase();
        $consulta = "SELECT a.id_area,a.nombre_area,a.descripcion,m.nombre AS nombre_municipalidad, COUNT(f.id_funcionario) AS total_funcionarios
            FROM area_municipal a
            LEFT JOIN funcionario_municipal f ON f.id_area_municipal = a.id_area LEFT JOIN municipalidad m ON m.id_municipalidad = a.id_municipalidad
            GROUP BY a.id_area,a.nombre_area,a.descripcion,m.nombre";

        return $db->query($consulta);
    }
    function borrarArea(int $id){
        $db = getDatabase();

        $db->execute("DELETE FROM area_municipal WHERE id_area = ?",[$id]);

        $resultado = $db->query("SELECT COUNT(*) AS total FROM area_municipal");

        if ((int)$resultado[0]['total'] === 0) {
            $db->execute("ALTER TABLE area_municipal AUTO_INCREMENT = 1");
        }

        return true;
    }


    class Departamento extends BaseModel{
        protected string $table = 'area_municipal';
        protected array $primaryKey = ['id_area'];
        protected array $columns = ['nombre_area', 'descripcion', 'id_municipalidad'];
        protected function fetchAll(string $sql, array $params = []): array {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }   
        public function findAll(): array {
            return $this->fetchAll(sprintf('SELECT * FROM %s', $this->table));
        }
    }

?>