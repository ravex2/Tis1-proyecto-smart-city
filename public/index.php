<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../controllers/template.controller.php";

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

// template:
$templatep = new TemplateController();
$templatep -> ctrtemplate();


