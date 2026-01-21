<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
// file autoLoader
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../core/Logger.php';
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../core/Router.php';

// Prefer Composer autoload when available (Twig, etc.)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}
require_once __DIR__ . '/../vendor/autoloadModel.php';
require_once __DIR__ . '/../vendor/autoloadRepository.php';


$router = new Router();
$router->handleRequest();
