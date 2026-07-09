<?php

require_once __DIR__ . '/basemodel.php';

class Sesion extends BaseModel {
    protected string $table = 'sesion';
    protected array $primaryKey = ['id_sesion'];
    protected array $columns = ['token_sesion', 'fecha_inicio', 'fecha_termino', 'tipo_sesion', 'rut_usuario','email_verificado'];

    public function __construct(?\PDO $pdo = null) {
        parent::__construct($pdo);
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

    public function findById(array|int $id): ?array {
        [$where, $params] = $this->buildWhereClause($id);
        return $this->fetch(sprintf('SELECT * FROM %s WHERE %s LIMIT 1', $this->table, $where), $params);
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
        echo $data;
        if (empty($data)) {
            return false;
        }

        [$where, $params] = $this->buildWhereClause($id);
        $set = implode(', ', array_map(fn($column) => sprintf('%s = ?', $column), array_keys($data)));

        return $this->execute(sprintf('UPDATE %s SET %s WHERE %s', $this->table, $set, $where), array_merge(array_values($data), $params));
    }

    public function verificarEmailPorRut(string $rut): bool {
        $sql = "UPDATE sesion SET email_verificado = 1 WHERE rut_usuario = ?";
        return $this->execute($sql, [$rut]);
    }

    public function delete(array|int $id): bool {
        [$where, $params] = $this->buildWhereClause($id);
        return $this->execute(sprintf('DELETE FROM %s WHERE %s', $this->table, $where), $params);
    }
    public function verificarEmailSesionModel(string $rut): ?array {
        $sql = "SELECT * FROM sesion 
                WHERE rut_usuario = ? 
                AND email_verificado = 1 
                LIMIT 1";
        
        return $this->fetch($sql, [$rut]);
    }

}
