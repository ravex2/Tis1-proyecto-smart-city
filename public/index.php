<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../controllers/template.controller.php";

define('BASE_PATH', realpath(__DIR__ . '/..'));

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();


if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$ruta = $_GET["ruta"] ?? "publicaciones";
$rutasPublicas = ['login', 'registro', 'publicaciones'];


if (!isset($_SESSION['user']) && !in_array($ruta, $rutasPublicas)) {
    $ruta = 'login';
}

$mapaRutas = [
    'login'  => '/views/base.php',
    'registro' => '/views/pages/registro.php',
    'logout' => '/views/pages/admin/logout.php',
    'dashboard' => '/views/pages/admin/panel_admin.php',
    'sector' => '/views/pages/admin/sector.php',
    'usuarios' => '/views/pages/admin/usuarios.php',
    'roles_usuarios' => '/views/pages/admin/asignacion_roles.php',
    'publicaciones' => '/src/publicaciones/feed_publicaciones.php',
    'departamentos' => '/views/pages/admin/area_municipal.php',
    'comercio' => '/views/pages/usuario/listado_comercio.php',
    'rubros' => '/views/pages/admin/rubros.php',
    'votaciones' => '/views/pages/admin/votaciones.php',
    'leer_publicacion' => '/src/publicaciones/leer_publicacion.php',
    'crear_publicacion' => '/src/publicaciones/crear_publicacion.php',
    'editar_publicacion' => '/src/publicaciones/editar_publicacion.php',
    'eliminar_publicacion' => '/src/publicaciones/eliminar_publicacion.php',

    'crear_reporte' => '/src/reportes/crear_reporte.php',

    'leer_mis_reportes' => '/src/reporte_ciudadano/leer_mis_reportes.php',
    'ver_reporte' => '/src/reporte_ciudadano/ver_reporte.php',

    'reportes' => '/src/reporte_funcionario/leer_reportes.php',
    'ver_reporte_funcionario' => '/src/reporte_funcionario/ver_reporte_funcionario.php',
    'seguimiento_reporte_funcionario' => '/src/reporte_funcionario/seguimiento_reporte_funcionario.php',


    'leer_categoria_publicacion' => '/src/categorias_publicaciones/leer_categoria_publicacion.php',
    'crear_categoria_publicacion' => '/src/categorias_publicaciones/crear_categoria_publicacion.php',
    'editar_categoria_publicacion' => '/src/categorias_publicaciones/editar_categoria_publicacion.php',
    'eliminar_categoria_publicacion' => '/src/categorias_publicaciones/eliminar_categoria_publicacion.php',

    'leer_categoria_reporte' => '/src/categorias_reporte/leer_categoria_reporte.php',
    'crear_categoria_reporte' => '/src/categorias_reporte/crear_categoria_reporte.php',
    'editar_categoria_reporte' => '/src/categorias_reporte/editar_categoria_reporte.php',
    'eliminar_categoria_reporte' => '/src/categorias_reporte/eliminar_categoria_reporte.php',

    'asignar_rol' => '/public/usuario_rol/editar.php',

    'eliminar_area' => '/public/areas_municipales/eliminar.php',
    'ingresar_area' => '/public/areas_municipales/ingresar.php',
    'editar_area' => '/public/areas_municipales/editar.php',
    'listar_area' => '/public/areas_municipales/listar.php',

    'gestion_comercio' => '/views/pages/admin/gestion_emprendimientos.php',
    'registrar_emprendimiento' => '/views/pages/usuario/comercio.php',
    'ingresar_emprendimiento' => '/public/negocios_locales/ingresar.php',
    'actualizar_revision' => '/public/negocios_locales/actualizar.php',
];

$archivoRelativo = $mapaRutas[$ruta] ?? '/views/pages/404.php';
$archivoCompleto = BASE_PATH . $archivoRelativo;

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {

    require_once $archivoCompleto;

} else {

    $template = new TemplateController();
    $template->ctrtemplate($archivoCompleto);

}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);