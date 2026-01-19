<?php
session_start();
require_once __DIR__ . '../../vendor/autoloadRepository.php';

class Router
{
    public function handleRequest()
    {
        require_once __DIR__ . '/../routes/web.php';

        $url = trim($_GET['url'] ?? '/', '/');

        foreach ($routes as $route => $action) {

            // 🔹 Route dynamique
            if (strpos($route, '{id}') !== false) {

                $pattern = preg_replace('#\{id\}#', '([0-9]+)', $route);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $url, $matches)) {
                    [$controller, $method] = $action;

                    require_once __DIR__ . "/../app/Controllers/$controller.php";

                    $controllerInstance = new $controller();
                    $controllerInstance->$method($matches[1]); // id
                    return;
                }
            }

            // 🔹 Route statique
            if ($route === $url) {
                [$controller, $method] = $action;

                require_once __DIR__ . "/../app/Controllers/$controller.php";

                $controllerInstance = new $controller();
                $controllerInstance->$method();
                return;
            }
        }

        die('404 - Page not found');
    }
}


