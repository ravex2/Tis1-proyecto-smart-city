<?php
require_once __DIR__ . '/../../controllers/usuario.controlador.php';

echo "Redirigiendo a Google para autenticación...";
$auth = new UsuarioController();
$auth->redirectToGoogle();