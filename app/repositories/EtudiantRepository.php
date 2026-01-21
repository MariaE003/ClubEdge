<?php
class EtudiantRepository{
    private $db ;
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function getEtudiantById($id){
        $stmt = $this->db->prepare("select * from users where id= ?");
        $stmt->execute([$id]);
        return UserFactory::create($stmt->fetch(PDO::FETCH_ASSOC));
    }
}