<?php 
class AuthController{
    private AuthRepository $repo ;
    public function __construct(){
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
    public function login() {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $result = $this->repo->login($email,$password);
        if ($result['success']) {
            if($result["user"]["role"] === "admin"){
                header("Location: admin/dashboard");
            }else if($result["user"]["role"] === "president"){
                header("Location: president/dashboard");
            }else{
                header("Location: etudiant/dashboard");
            }
            exit();   
        } else {
            header("Location: index");
            exit();      
        }
    }
    public function register() {
        $register = new Register(   
            null,
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['password'],
        "etudiant",
        $_POST['urlImage']
    );
        $result = $this->repo->register($register); 

    if ($result['success']) {
        header("Location: loginPage");
        exit();   
    } else {
        header("Location: registerPage");
        exit();      
    }
    }

    public function logout() {
        session_destroy();
        header('Location: index');
    }
}