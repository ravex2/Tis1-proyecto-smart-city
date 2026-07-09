<?php

require_once __DIR__ . '/basemodel.php';

class PasswordReset extends BaseModel {
    protected string $table = 'password_resets';
    protected array $primaryKey = ['id_reset'];
    protected array $columns = ['correo', 'token_hash', 'expires_at', 'created_at'];

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

    public function findValidByToken(string $token): ?array {
        $tokenHash = hash('sha256', $token);
        $sql = "SELECT * FROM {$this->table} WHERE token_hash = ? AND expires_at >= ? LIMIT 1";
        return $this->fetch($sql, [$tokenHash, date('Y-m-d H:i:s')]);
    }

    public function deleteByEmail(string $correo): bool {
        return $this->execute("DELETE FROM {$this->table} WHERE correo = ?", [$correo]);
    }

    public function deleteByToken(string $token): bool {
        $tokenHash = hash('sha256', $token);
        return $this->execute("DELETE FROM {$this->table} WHERE token_hash = ?", [$tokenHash]);
    }
}
