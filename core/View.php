<?php

final class View
{
    private static ?object $twig = null;

    public static function render(string $template, array $context = []): void
    {
        if (!class_exists(\Twig\Environment::class)) {
            http_response_code(500);
            echo "Twig n'est pas installé. Exécute: composer require twig/twig && composer install";
            return;
        }

        $twig = self::twig();
        echo $twig->render($template, $context);
    }

    public static function basePath(): string
    {
        $script = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
        $script = str_replace('\\', '/', $script);
        $base = rtrim(dirname($script), '/');
        if ($base === '.' || $base === '') {
            return '';
        }
        return $base;
    }

    public static function url(string $path): string
    {
        return self::basePath() . '/' . ltrim($path, '/');
    }

    public static function asset(string $path): string
    {
        return self::basePath() . '/assets/' . ltrim($path, '/');
    }

    private static function twig(): \Twig\Environment
    {
        if (self::$twig instanceof \Twig\Environment) {
            return self::$twig;
        }

        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/views/twig');
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
            'autoescape' => 'html',
        ]);

        $twig->addFunction(new \Twig\TwigFunction('path', fn (string $p): string => self::url($p)));
        $twig->addFunction(new \Twig\TwigFunction('asset', fn (string $p): string => self::asset($p)));
        $twig->addFunction(new \Twig\TwigFunction('csrf_token', fn (): string => Csrf::token()));

        $twig->addGlobal('session', $_SESSION ?? []);

        self::$twig = $twig;
        return $twig;
    }
}

