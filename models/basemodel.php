<?php

require_once __DIR__ . '/../config/database.php';

class BaseModel {
    protected \PDO $pdo;

    public function __construct(?\PDO $pdo = null) {
        if ($pdo) {
            $this->pdo = $pdo;
            return;
        }

        $db = getDatabase();
        $this->pdo = $db->connection();
    }

    protected function getPdo(): \PDO {
        return $this->pdo;
    }
}
