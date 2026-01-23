<?php
class EtudiantController extends BaseController{
    private EtudiantRepository $repoEtu;

    public function __construct(){
        parent::__construct();
        $pdo=Database::getInstance()->getConnection();
        $this->repoEtu=new EtudiantRepository($pdo);
    }

    public function dashboard(){
        require_once __DIR__."/../views/student/dashboard.html";
    }

    public function getEtudiantById($id){
        $etud=$this->repoEtu->EtudiantRepository();
        echo $this->render('student/dashboard.html',[$admin]);
    }
}