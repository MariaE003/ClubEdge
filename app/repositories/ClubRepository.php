<?php

class ClubRepository{
    private PDO $db;
    public function __construct(PDO $db){
        $this->db=$db;
    }
    // ajouter un club
    public function addClub(Club $club){
        $req=$this->db->prepare("INSERT into clubs(name,description,president_id,members,logo) values(?,?,?,?,?)");
        // getMember
        return $req->execute([$club->getNom(),$club->getDescription(),$club->getPresidentId(),'{'. implode(',',$club->getMembers()).'}',$club->getLogo()]);
    }
    // affichier tous les club
    public function findAllClubs(){
        $req=$this->db->prepare("SELECT * from clubs");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    // supprimer un clubs
    public function removeClub(int $id){
    $req=$this->db->prepare("DELETE from clubs where id=?");
        return $req->execute([$id]);
    }
    // modifier un club
    public function updateClub(Club $club){
    $req=$this->db->prepare("UPDATE clubs set name=?,description=?,members=?,logo=? where id=?");
    // member
        return $req->execute([$club->getNom() ,$club->getDescription(),'{'. implode(',',$club->getMembers()).'}',$club->getLogo(),$club->getId()]);
    }

    // rechercher sur un club ?
}
?>