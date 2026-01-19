<?php
class Register extends User{

    public function __construct($id, string $nom, string $prenom, string $email, string $password, string $role, string|null $image = null){
        parent::__construct($id,$nom , $prenom , $email , $password , $role , $image);
    }

    public function registerUser(){
        return parent::register();
    }

}