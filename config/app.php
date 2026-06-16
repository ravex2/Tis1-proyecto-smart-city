<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/twig.php';

define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_city');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_NAME', 'Smart City');
define('DB_PORT', 3307);


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);