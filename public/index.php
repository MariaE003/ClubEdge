<?php
ob_start();
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../core/Logger.php';
require_once __DIR__ . '/../core/View.php';

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoloadModel.php';
require_once __DIR__ . '/../vendor/autoloadRepository.php';
require_once __DIR__ . '/../vendor/autoloadFactory.php';

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/BaseController.php';

$router = new Router();
$router->handleRequest();

ob_end_flush();
