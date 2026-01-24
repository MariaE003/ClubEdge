<?php
class EtudiantController extends BaseController{
    public function __construct(){
        parent::__construct();
        $this->requireRole1('etudiant');
    }
    public function dashboard(){
        require_once __DIR__."/../views/student/dashboard.html";
    }

    public function getEtudiantById($id){
        
    }
}