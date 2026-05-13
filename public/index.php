<?php
require_once __DIR__ . "/../controllers/template.controller.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

$templatep = new TemplateController();
$templatep -> ctrtemplate();