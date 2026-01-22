<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class BaseController
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader, [
            'cache' => false,
            'autoescape' => 'html',
        ]);

        $this->twig->addFunction(new TwigFunction('path', fn (string $p): string => View::url($p)));
        $this->twig->addFunction(new TwigFunction('asset', fn (string $p): string => View::asset($p)));
        $this->twig->addFunction(new TwigFunction('csrf_token', fn (): string => Csrf::token()));

        $this->twig->addGlobal('session', $_SESSION ?? []);
    }

    protected function render(string $view, array $data = []): void
    {
        echo $this->twig->render($view, $data);
    }
}
