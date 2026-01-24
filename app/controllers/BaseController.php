<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
class BaseController{
    protected Environment  $twig;
    public function __construct(){
        $loader=new FilesystemLoader(__DIR__ . '/../views');
        $this->twig=new Environment($loader);
    }

    protected function render(string $view, array $data = []): void
{
        $this->twig->addFunction(new \Twig\TwigFunction('path', function ($route, $params = []) {
            $url = '/ClubEdge/' . str_replace('_', '/', $route);

            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $url .= '/' . $value;
                }
            }

            return $url;
        }));

    echo $this->twig->render($view, $data);
}

    protected function checkSession(): void
    {
        // Pas connecté
        if (
            empty($_SESSION['user_id']) ||
            empty($_SESSION['role'])
        ) {
            header('Location: /ClubEdge/loginPage');
            exit;
        }
    }

    protected function redirectToDashboard(): void
    {
        $role = $_SESSION['role'] ?? null;

        if (!$role) {
            header('Location: /ClubEdge/loginPage');
            exit;
        }

        switch ($role) {
            case 'admin':
                header('Location: /ClubEdge/admin/dashboard');
                break;

            case 'president':
                header('Location: /ClubEdge/president/dashboard');
                break;

            case 'etudiant':
                header('Location: /ClubEdge/etudiant/dashboard');
                break;

            default:
                header('Location: /ClubEdge/loginPage');
        }

        exit;
    }

    protected function requireRole1(string $role): void
    {
        $this->checkSession();

        if ($_SESSION['role'] !== $role) {
            // Sécurité : mauvais rôle
            $this->redirectToDashboard();
        }
    }   

    }
