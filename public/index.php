<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../controllers/template.controller.php";

define('BASE_PATH', realpath(__DIR__ . '/..'));

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$ruta = $_GET["ruta"] ?? "login";
$rutasPublicas = ['login', 'registro'];

/*
if (!isset($_SESSION['user']) && !in_array($ruta, $rutasPublicas)) {
    $ruta = 'login'; // Fuerza el login si no hay sesión
}
*/


$mapaRutas = [
    'login'  => '/views/base.php',
    'registro' => '/views/pages/registro.php',
    'inicio' => '/views/pages/admin/panel.php',
    'reportes' => '/views/pages/admin/reportes.php',
    'sector' => '/views/pages/admin/sector.php',
    'logout' => '/views/pages/auth/logout.php',
    'usuarios' => '/views/pages/admin/usuarios.php',
    'publicaciones' => '/views/pages/admin/publicaciones.php',
    'departamentos' => '/views/pages/admin/area_municipal.php',
    'comercio' => '/views/pages/admin/comercio.php',
    'rubros' => '/views/pages/admin/rubros.php',
    'votaciones' => '/views/pages/admin/votaciones.php',
];

$archivoRelativo = $mapaRutas[$ruta] ?? '/views/pages/404.php';
$archivoCompleto = BASE_PATH . $archivoRelativo;


$template = new TemplateController();
$template->ctrtemplate($archivoCompleto);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);