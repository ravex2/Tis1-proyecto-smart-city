<?php

declare(strict_types=1);

namespace Proyecto\core;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct(array $config) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['dbname']
        );

        $this->pdo = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function connection(): PDO {
        return $this->pdo;
    }

    public function query(
        string $sql,
        array $params = []
    ): array {
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function execute(
        string $sql,
        array $params = []
    ): bool {
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }
}