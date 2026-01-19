<?php 
class AuthController{
    public function pageHome(){
        require_once __DIR__. "/../views/home.html";
    }
    public function pageRegister(){
        require_once __DIR__. "/../views/auth/register.html";
    }
    public function pageLogin(){
        require_once __DIR__. "/../views/auth/login.html";
    }
}