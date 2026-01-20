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
    public function countMembers(){
        $sql_prepare="SELECT cardinality(members) as total from clubs where id=?";
        $sql=$this->db->prepare($sql_prepare);
        $sql->execute([$this->id]);
        $result=$sql->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    
    public function isStudentInClub($user_id){
    $sql_prepare="SELECT count(*) where ? = ANY(members)";
    $sql=$this->db->prepare($sql_prepare);
    $sql->execute([$user_id]);
    $result=$sql->fetchColumn();
    return $result>0;
    }




    public function canJoin(){
        $count_members=$this->countMembers();
        if($count_members>=8){
            return false;
        }else{
            return true;
        }
    }


    public function joinClub($user_id){
        try{

            $this->db->beginTransaction();

            $currentCount=$this->countMembers();
            
            $sql_prepare="UPDATE clubs SET members =array_append(members,?) where id=?";
            $sql=$this->db->prepare($sql_prepare);
            $sql->execute([$user_id,$this->id]);

            if($currentCount==0){
                $sql_pre="UPDATE clubs set president_id=? where id=?";
                $sql=$this->db->prepare($sql_pre);
                $sql->execute([$user_id,$this->id]);

                $prepareRole="UPDATE users set role='president' where id=?";
                $sqlRole=$this->db->prepare($prepareRole);
                $sqlRole->execute([$user_id]);
            }
            $this->db->commit();
            
        }catch(Exception $e){
            $this->db->rollBack();
            return $e->getMessage();
            
        }

    
}
}