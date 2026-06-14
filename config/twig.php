<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/../cache/twig',
    'debug' => true, // Habilitar el modo debug para desarrollo
]);

?>
