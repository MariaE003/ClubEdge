<?php 
class AuthController extends BaseController{
    private AuthRepository $repo ;
    public function __construct(){
        parent::__construct();
        $this->repo = new AuthRepository();
    }
    public function pageHome(){
        require_once __DIR__. "/../../app/views/home.html";
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
        require_once __DIR__."/../views/president/dashboard.twig";
    }
    // public function login() {
    //     $email = $_POST["email"];
    //     $password = $_POST["password"];
    //     $result = $this->repo->login($email,$password);
    //     if ($result['success']) {
    //         if($result["user"]["role"] === "admin"){
    //            $this->dashboardAdmin();
    //         }else if($result["user"]["role"] === "president"){
    //             $this->dashboardPresident();
    //         }else{
    //             // mohssine
    //             header("Location: student/clubs-list");
    //            $this->dashboardEtudiant();
    //         }
    //         exit();   
    //     } else {
    //         $this->pageLogin();
    //         exit();      
    //     }
    // }
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
            $this->pageLogin();
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
        $this->pageLogin();
        exit();   
    } else {
        $this->pageRegister();
        exit();      
    }
    }

    public function logout() {
        session_destroy();
        header('Location: index');
    }

    public function testConnection(){
        require_once __DIR__. "/../../core/Database.php";
    }

    
}