<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../vendor/autoloadModel.php';
require_once __DIR__ . '/../vendor/autoloadRepository.php';

$router = new Router();
$router->handleRequest();