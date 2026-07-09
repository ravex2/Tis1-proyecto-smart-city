<?php

require_once __DIR__ . '/basemodel.php';

class Usuario extends BaseModel {
    protected string $table = 'usuario';
    protected array $primaryKey = ['rut'];
    protected array $columns = ['nombre', 'apellido', 'correo', 'direccion', 'contrasenha', 'id_rol', 'id_sector'];

    public function __construct(?\PDO $pdo = null) {
        parent::__construct($pdo);
    }

    public function findByCorreo(string $correo): ?array {
        return $this->fetch('SELECT u.* , r.tipo_interfaz FROM usuario u JOIN rol r ON u.id_rol = r.id_rol WHERE correo = ? LIMIT 1', [$correo]);
    }

    protected function fetch(string $sql, array $params = []): ?array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    protected function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    protected function execute(string $sql, array $params = []): bool {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    protected function filterData(array $data): array {
        return array_intersect_key($data, array_flip($this->columns));
    }

    protected function buildWhereClause(array|int $id): array {
        if (is_int($id) && count($this->primaryKey) === 1) {
            return [$this->primaryKey[0] . ' = ?', [$id]];
        }

        if (!is_array($id)) {
            throw new InvalidArgumentException('Invalid identifier for model lookup.');
        }

        $clauses = [];
        $params = [];

        foreach ($this->primaryKey as $key) {
            if (!array_key_exists($key, $id)) {
                throw new InvalidArgumentException(sprintf('Missing primary key column "%s".', $key));
            }
            $clauses[] = sprintf('%s = ?', $key);
            $params[] = $id[$key];
        }

        return [implode(' AND ', $clauses), $params];
    }

    public function findAll(): array {
        return $this->fetchAll(sprintf('SELECT * FROM %s', $this->table));
    }

    public function findAllWithRoles(): array {
            return $this->fetchAll("SELECT u.rut,u.nombre, u.apellido, u.correo, u.id_rol,r.nombre_rol FROM usuario u JOIN rol r ON u.id_rol = r.id_rol");
    }

    public function obtenerPermisosPorRol(int $id_rol): array {
        $consulta = "SELECT p.nombre_permiso 
                    FROM permiso p 
                    JOIN posee po ON p.id_permiso = po.id_permiso 
                    WHERE po.id_rol = ?";

        $resultado = $this->fetchAll($consulta, [$id_rol]) ?? [];

        return array_column($resultado, 'nombre_permiso');
    }

    public function findById(array|int $id): ?array {
        [$where, $params] = $this->buildWhereClause($id);
        return $this->fetch(sprintf('SELECT * FROM %s WHERE %s LIMIT 1', $this->table, $where), $params);
    }
    public function countAll(): int {
        $row = $this->fetch("SELECT COUNT(*) AS total FROM {$this->table}");
        return (int) $row['total'];
    }

    public function create(array $data): string {
        $data = $this->filterData($data);
        if (empty($data)) {
            throw new InvalidArgumentException('No valid columns provided for insert.');
        }

        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->table, implode(', ', $columns), $placeholders);

        $this->execute($sql, array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function update(array|int $id, array $data): bool {
        $data = $this->filterData($data);
        if (empty($data)) {
            return false;
        }

        [$where, $params] = $this->buildWhereClause($id);
        $set = implode(', ', array_map(fn($column) => sprintf('%s = ?', $column), array_keys($data)));

        return $this->execute(sprintf('UPDATE %s SET %s WHERE %s', $this->table, $set, $where), array_merge(array_values($data), $params));
    }

    public function delete(array|int $id): bool {
        [$where, $params] = $this->buildWhereClause($id);
        return $this->execute(sprintf('DELETE FROM %s WHERE %s', $this->table, $where), $params);
    }
}
