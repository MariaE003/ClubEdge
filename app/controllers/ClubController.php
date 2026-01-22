<?php
require __DIR__.'/BaseController.php';

class ClubController extends BaseController{

    private ClubRepository $repoClub;

    public function __construct(){
        parent::__construct();
        $pdo=Database::getInstance()->getConnection();
        $this->repoClub = new ClubRepository($pdo);
    }
    // formulaire d'ajou
    // public function pageAddClubs(){
    //     $this->render('student/');
    // }

    // voir les clubs 

    public function direction_clubList(){
        require_once __DIR__."../../views/student/clubs-list.twig";
    }


    public function pageClubs(){
        $this->render('admin/create-club.html');
    }
    //     public function direction_clubs(){
    //     require_once __DIR__."../../views/student/clubs-list.html";
    // }

    // public function direction_events(){
    //     require_once __DIR__."../../views/student/events-list.html";
    // }

    // public function pageUpdateClubs(){
        
    // }

    // pour affichage des clubs
    public function AfficherClub(){
        $clubs=$this->repoClub->allClubs();
        // var_dump($clubs);
        // var_dump($clubs);
        echo $this->render('student/clubs-list.twig',[
            'clubs'=>$clubs,
        ]);
    }
    // detai dun club
    public function detailClub(){
        
        if (!isset($_GET['idC'])) {
            die('club introvalbe !');
        }
        
        $idClub=(int)$_GET['idC'];
        $events = $this->repoClub->findEventByClub($idClub);
        $club=$this->repoClub->findClubById($idClub);
        $result=$this->repoClub->countMembers($idClub);
        $nombre_members=$result['total'];
        // var_dump($club['members']);
        // var_dump($club['members']);
        // var_dump($club['members']);
        // var_dump($club);
        $NameClub=strtoupper(substr($club['name'],0,2));
        echo $this->render('student/club-details.twig',[
            'club'=>$club,
            'NameClub'=>$NameClub,
            'events'=>$events,
            'members' =>$nombre_members
        ]);
        
    }

    
   

    // public function AjouterClub(){
    //     $this->repoClub->addClub();
    // } 
    


    public function join(){
        $user_id = $_SESSION['user_id']; 
        $club_id = $_GET['idC'];

        if (!$this->repoClub->canJoin($club_id)) {
        die("Désolé, ce club est déjà complet (max 8 membres).");
    }

    if ($this->repoClub->isStudentInClub($user_id, $club_id)) {
        die("Vous êtes déjà membre de ce club.");
    }

    $result = $this->repoClub->joinClub($user_id, $club_id);


    header("Location: /club/detail?idC=" . $club_id);
    exit();
    }


    public function nb_members(){
        $club_id=$_GET['idC'];
        
        $club = $this->repoClub->findClubById($club_id);
        $events = $this->repoClub->findEventByClub($club_id);
        
        echo $this->render('student/club-details.twig', [
        'club'          => $club,
        // 'nombreMembres' => $nombre_members,
        'events'        => $events, // HADOU HUMA LES EVENTS LI GHADI Y-LISTIW
        'NameClub'      => strtoupper(substr($club['name'], 0, 2))
    ]);
    }


}


    

?>