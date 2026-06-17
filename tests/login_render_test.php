<?php
// Test script: simulate successful login and render inicio.twig
// Load Composer autoloader first so Twig classes are available
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/twig.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simulate a logged-in user (do not include sensitive data)
$_SESSION['user'] = [
    'id' => 1,
    'nombre' => 'Test User',
    'correo' => 'test@example.com'
];

// Render the panel inicio template
echo $twig->render('pages/panel/inicio.twig', [
    'user' => $_SESSION['user']
]);
