<?php

require_once __DIR__ . '/basemodel.php';

class Participacion extends BaseModel {
    protected string $table = 'participacion';
    protected array $primaryKey = ['id_participacion'];
    protected array $columns = ['fecha_participacion', 'id_voto'];

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

    public function getParticipacionMensual(string $fechaDesde, string $fechaHasta): array {
        $sql = "
            SELECT
                DATE_FORMAT(pa.fecha_participacion, '%Y-%m') AS periodo,
                COUNT(DISTINCT par.rut_usuario) AS total_participantes
            FROM participacion pa
            INNER JOIN participa par ON pa.id_participacion = par.id_participacion
            WHERE pa.fecha_participacion >= ? AND pa.fecha_participacion <= ?
            GROUP BY DATE_FORMAT(pa.fecha_participacion, '%Y-%m')
            ORDER BY periodo ASC
        ";

        return $this->fetchAll($sql, [$fechaDesde . ' 00:00:00', $fechaHasta . ' 23:59:59']);
    }
}
