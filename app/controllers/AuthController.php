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
            if($result["user"]["role"] === "coach"){
                header("Location: coach/dashboard");
            }else{
                header("Location: sportif/dashboard");
            }
            exit();   
        } else {
            header("Location: index");
            exit();      
        }
    }
    public function register() {
        $register = new Register(
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['email'],
        $_POST['password'],
        $_POST['role'],
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