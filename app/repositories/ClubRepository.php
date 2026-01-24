<?php

class ClubRepository{
    private PDO $db;
    public function __construct(PDO $db){
        $this->db=$db;
    }
    public function findIdByPresidentId(int $presidentId): ?int
    {
        $stmt = $this->db->prepare("SELECT id FROM clubs WHERE president_id = ? LIMIT 1");
        $stmt->execute([$presidentId]);
        $id = $stmt->fetchColumn();
        if ($id === false) {
            return null;
        }
        return (int) $id;
    }
    // ajouter un club
    public function addClub(Club $club){
        $req=$this->db->prepare("INSERT into clubs(name,description,president_id,members,logo) values(?,?,?,?,?)");
        // getMember
        return $req->execute([$club->getName(),$club->getDescription(),$club->getPresidentId(),'{}',$club->getLogo()]);
    }
    // affichier tous les club
    public function allClubs(){
        $req=$this->db->prepare("SELECT * from clubs");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllClub(){
        $req=$this->db->prepare("SELECT * from clubs order by created_at desc");
        $req->execute();
        $result =  $req->fetchAll(PDO::FETCH_ASSOC);
        $list = [];
        foreach($result as $r){
            $list[] = ClubFactory::fromDbRow($r);
        }
        return $list;
    }

    // find
    public function findClubById($idClub){
        $req=$this->db->prepare("SELECT * from clubs where id=?");
        $req->execute([$idClub]);
        return $req->fetch(PDO::FETCH_ASSOC);
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
        return $req->execute([$club->getName() ,$club->getDescription(),'{'. implode(',',$club->getMembers()).'}',$club->getLogo(),$club->getId()]);
    }
    // le nombre des club pour virifier si admina depasse la limites des club ou non
    public function countClubs(){
        $req = $this->db->prepare("SELECT count(*) from clubs");
        $req->execute();
        return (int) $req->fetchColumn();
    }
    // search
    public function searchClubByName($name){
        $req = $this->db->prepare("SELECT * from clubs where name=?");
        $req->execute([$name]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // les evenemet dun club
    public function findEventByClub(int $clubId): array {
        $sql = "SELECT * FROM events WHERE club_id=? ORDER BY event_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clubId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //
    public function clubMembers($id){
        $sql = "SELECT u.id, u.nom, u.prenom, u.email, u.role FROM users u
        JOIN clubs c ON u.id = ANY(c.members) WHERE c.id = ? ORDER BY u.nom";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    public function countMembers($club){
        $sql_prepare="SELECT cardinality(members) as total from clubs where id=?";
        $sql=$this->db->prepare($sql_prepare);
        $sql->execute([$club]);
        $result=$sql->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    

    
    public function isStudentInClub($club_id,$user_id){
    $sql_prepare="SELECT count(*) from clubs where id=? and ? = ANY(members)";
    $sql=$this->db->prepare($sql_prepare);
    $sql->execute([$club_id,$user_id]);
    $result=$sql->fetchColumn();
    return $result>0;
    }


    public function isStudentInAnyClub($user_id) {
    // Kat-qleb f ga3 les clubs wach had l-user_id kayn f ay array 'members'
    $sql = "SELECT COUNT(*) FROM clubs WHERE ? = ANY(members)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$user_id]);
    
    // fetchColumn() ghadi t-rje3 lina 0 (false) awla 1 o k-ter (true)
    return (int)$stmt->fetchColumn() > 0;
}




    public function canJoin($club_id){
        $count_members=$this->countMembers($club_id);
        if($count_members<=8){
            return false;
        }else{
            return true;
        }
    }


    public function joinClub($user_id,$club_id){
        try{

            $this->db->beginTransaction();

            $result=$this->countMembers($club_id);
            $currentCount = (int)$result['total'];

            if ($currentCount >= 8) {
            throw new Exception("Le club est plein.");
        }
            
            $sql_prepare="UPDATE clubs SET members =array_append(members,?) where id=?";
            $sql=$this->db->prepare($sql_prepare);
            $sql->execute([$user_id,$club_id]);

            if($currentCount==0){
                $sql_pre="UPDATE clubs set president_id=? where id=?";
                $sql=$this->db->prepare($sql_pre);
                $sql->execute([$user_id,$club_id]);

                $prepareRole="UPDATE users set role='president' where id=?";
                $sqlRole=$this->db->prepare($prepareRole);
                $sqlRole->execute([$user_id]);
                $_SESSION['role'] = 'president';
            }
            $this->db->commit();
            
        }catch(Exception $e){
            $this->db->rollBack();
            return $e->getMessage();
            
        }
    }

}
