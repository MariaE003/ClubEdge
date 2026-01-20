<?php
class AdminRepository{


    public function totalEtudiants(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("select count(*) as total from users where role = etudiant ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function totalClub(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("select count(*) as total from clubs");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function totalEvenements(){
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("select count(*) as total from events");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}