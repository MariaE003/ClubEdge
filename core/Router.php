<?php
class Router
{
    public function handleRequest()
    {
        require_once __DIR__ . '/../routes/web.php';

        $rawUrl = $_GET['url'] ?? null;
        if (!is_string($rawUrl) || $rawUrl === '') {
            $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '');
            $rawUrl = (string) (parse_url($requestUri, PHP_URL_PATH) ?? '');
        }

        $base = class_exists(View::class) ? View::basePath() : '';
        if ($base !== '' && str_starts_with($rawUrl, $base)) {
            $rawUrl = substr($rawUrl, strlen($base));
        }
        $rawUrl = preg_replace('#^/index\.php#', '', $rawUrl) ?? $rawUrl;

        $url = trim($rawUrl, '/');
        if ($url === '') {
            $url = '/';
        }

        foreach ($routes as $route => $action) {

            if (strpos($route, '{id}') !== false) {

                $pattern = preg_replace('#\{id\}#', '([0-9]+)', $route);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $url, $matches)) {
                    [$controller, $method] = $action;

                    require_once __DIR__ . "/../app/controllers/$controller.php";

                    $controllerInstance = new $controller();
                    $controllerInstance->$method($matches[1]); 
                    return;
                }
            }

            if ($route === $url) {
                [$controller, $method] = $action;

                require_once __DIR__ . "/../app/controllers/$controller.php";

                $controllerInstance = new $controller();
                $controllerInstance->$method();
                return;
            }
        }

        http_response_code(404);
        $phpView = __DIR__ . '/../app/views/errors/404.php';
        if (is_file($phpView)) {
            require_once $phpView;
            return;
        }

        require_once __DIR__ . '/../app/views/errors/404.html';
        return;
    }
}

