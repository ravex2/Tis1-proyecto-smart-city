<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION = array(); // Vacía los datos
    session_destroy();   // Destruye el archivo de sesión
    // borrar la sesion de cookie si es que existe
    header('Location: ?ruta=login');
    exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Cerrando sesion
</body>
</html>