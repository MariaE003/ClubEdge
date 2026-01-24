    <?php
    class AdminRepository{
        private $db;
        public function __construct(){
            $this->db  = Database::getInstance()->getConnection();
        }


        public function totalEtudiants(){
            $stmt = $this->db->prepare("select count(*) as total from users where role = 'etudiant' ");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
        }
        public function totalClub(){
            $stmt = $this->db->prepare("select count(*) as total from clubs");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
        }
        public function totalEvenements(){
            $stmt = $this->db->prepare("select count(*) as total from events");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)["total"];
        }

        public function getAllEtudiant(){
            $stmt = $this->db->prepare("select * from users where role = 'etudiant' or role = 'president'");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $list  = [];
            foreach($result as $e){
                if($e["role"] == 'etudiant'){
                $list[]= new Etudiant($e["id"],$e["nom"],$e["prenom"] , $e["email"] , $e["password"] , $e["role"] , $e["profile"] , $e["date_creation"]);
                }else{
                    $list[]= new President($e["id"],$e["nom"],$e["prenom"] , $e["email"] , $e["password"] , $e["role"] , $e["profile"] , $e["date_creation"]);
                }
            }
        return $list;
        }
        public function supprimerEtudiant($id){
            $stmt = $this->db->prepare("delete from users where id=?");
            return $stmt->execute([$id]);
        }
        public function searchEtudiants(string $q): array
{
    $q = trim($q);

    if ($q === '') {
        return $this->getAllEtudiant();
    }

    $stmt = $this->db->prepare("
        SELECT *
        FROM users
        WHERE role IN ('etudiant','president')
          AND (nom ILIKE :q OR prenom ILIKE :q OR email ILIKE :q)
        ORDER BY date_creation DESC
        LIMIT 100
    ");

    $stmt->execute([':q' => "%{$q}%"]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $list = [];
    foreach ($result as $e) {
        // IMPORTANT: === pas =
        if ($e['role'] === 'etudiant') {
            $list[] = new Etudiant($e["id"], $e["nom"], $e["prenom"], $e["email"], $e["password"], $e["role"], $e["profile"], $e["date_creation"]);
        } else {
            $list[] = new President($e["id"], $e["nom"], $e["prenom"], $e["email"], $e["password"], $e["role"], $e["profile"], $e["date_creation"]);
        }
    }
    return $list;
}
        

    }