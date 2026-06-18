<?php

    require_once __DIR__ . "/../core/database.php";
    require_once __DIR__ . "/app.php";

    use Proyecto\core\Database;
    
    function getDatabase(): Database {
        $config = [
            'host' => DB_HOST,
            'port' => DB_PORT,
            'dbname' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASS,
        ];
        return new Database($config);
    }
?> 