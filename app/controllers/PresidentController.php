<?php
class PresidentController{
    public function dashboard(){
        require_once __DIR__."/../views/president/dashboard.html";
    }

    public function testTwig(){
        require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/views');
$twig = new \Twig\Environment($loader);

echo $twig->render('test.twig', [
    'name' => 'Othmane'
]);
    }
}