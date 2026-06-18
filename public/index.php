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
    'dashboard' => '/views/pages/admin/panel_admin.php',
    'reportes' => '/views/pages/admin/reportes.php',
    'sector' => '/views/pages/admin/sector.php',
    'logout' => '/views/pages/auth/logout.php',
    'usuarios' => '/views/pages/admin/usuarios.php',
    'publicaciones' => '/src/publicaciones/feed_publicaciones.php',
    'departamentos' => '/views/pages/admin/area_municipal.php',
    'comercio' => '/views/pages/admin/comercio.php',
    'rubros' => '/views/pages/admin/rubros.php',
    'votaciones' => '/views/pages/admin/votaciones.php',
    'leer_publicacion' => '/src/publicaciones/leer_publicacion.php',
    'crear_publicacion' => '/src/publicaciones/crear_publicacion.php',
    'editar_publicacion' => '/src/publicaciones/editar_publicacion.php',
    'eliminar_publicacion' => '/src/publicaciones/eliminar_publicacion.php',

    'leer_categoria_publicacion' => '/src/categorias_publicaciones/leer_categoria_publicacion.php',
    'crear_categoria_publicacion' => '/src/categorias_publicaciones/crear_categoria_publicacion.php',
    'editar_categoria_publicacion' => '/src/categorias_publicaciones/editar_categoria_publicacion.php',
    'eliminar_categoria_publicacion' => '/src/categorias_publicaciones/eliminar_categoria_publicacion.php',
];

$archivoRelativo = $mapaRutas[$ruta] ?? '/views/pages/404.php';
$archivoCompleto = BASE_PATH . $archivoRelativo;

$template = new TemplateController();
$template->ctrtemplate($archivoCompleto);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);