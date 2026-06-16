<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private \PDO $pdo;

    public function __construct(?\PDO $pdo = null) {
        if ($pdo) {
            $this->pdo = $pdo;
            return;
        }

        $db = getDatabase();
        $this->pdo = $db->connection();
    }

    public function findByCorreo(string $correo): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE correo = ? LIMIT 1');
        $stmt->execute([$correo]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
