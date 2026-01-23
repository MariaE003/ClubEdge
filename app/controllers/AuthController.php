<?php 
class AuthController extends BaseController{
    private AuthRepository $repo ;
    public function __construct(){
        parent::__construct();
        $this->repo = new AuthRepository();
    }
    public function pageHome(){
        require_once __DIR__. "/../views/home.html";
    }
    public function pageRegister(){
        require_once __DIR__. "/../views/auth/register.html";
    }
    public function pageLogin(){
        require_once __DIR__. "/../views/auth/login.html";
    }
    public function dashboardAdmin(){
        require_once __DIR__. "/../views/admin/dashboard.twig";
    }
    public function dashboardEtudiant(){
        require_once __DIR__."/../views/student/dashboard.html";
    }
    public function dashboardPresident(){
        require_once __DIR__."/../views/president/dashboard.html";
    }
    private function redirect(string $path): void
    {
        header('Location: ' . View::url($path));
        exit();
    }

    public function login(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->pageLogin();
            return;
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->redirect('loginPage');
        }

        $result = $this->repo->login($email, $password);
        if (($result['success'] ?? false) && isset($result['user']['role'])) {
            $role = (string) $result['user']['role'];
            if ($role === "admin") {
                $this->redirect('admin/dashboard');
            }
            if ($role === "president") {
                $this->redirect('president/dashboard');
            }
            $this->redirect('etudiant/dashboard');
        }

        $this->redirect('loginPage');
    }
    public function register() {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->pageRegister();
            return;
        }

        $nom = trim((string) ($_POST['nom'] ?? ''));
        $prenom = trim((string) ($_POST['prenom'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $urlImage = trim((string) ($_POST['urlImage'] ?? ''));
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '' || $password === '' || $urlImage === '') {
            $this->redirect('registerPage');
        }
        if ($passwordConfirm !== '' && $passwordConfirm !== $password) {
            $this->redirect('registerPage');
        }

        $register = new Register(   
            null,
        $nom,
        $prenom,
        $email,
        $password,
        "etudiant",
        $urlImage
    );
        $result = $this->repo->register($register); 

    if ($result['success']) {
        $this->redirect('loginPage');
    } else {
        $this->redirect('registerPage');
    }
    }

    public function logout() {
        session_destroy();
        header('Location: index');
    }
}
