
<?php
class ClubRepository{
    
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
?>