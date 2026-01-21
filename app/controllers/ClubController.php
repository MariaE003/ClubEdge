<?php
class ClubController extends BaseController{

    private ClubRepository $repoClub;

    public function __construct(){
        parent::__construct();
        $pdo=Database::getInstance()->getConnection();
        $this->repoClub=new ClubRepository($pdo);
    }
    // formulaire d'ajou
    // public function pageAddClubs(){
    //     $this->render('student/');
    // }

    // voir les clubs 
    public function pageClubs(){
        $this->render('admin/create-club.html');
    }

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
        $club=$this->repoClub->findClubById($idClub);
        var_dump($club['members']);
        var_dump($club['members']);
        var_dump($club['members']);
        // var_dump($club);
        $NameClub=strtoupper(substr($club['name'],0,2));
        echo $this->render('student/club-details.twig',[
            'club'=>$club,
            'NameClub'=>$NameClub,
        ]);
        
    }

    // les evnet des club
    public function listEventByClub(int $clubId) {
        $events = $this->eventRepo->findEventByClub($clubId);

        $this->render('events/list', [
            'events' => $events,
            'clubId' => $clubId
        ]);
    }

    // public function AjouterClub(){
    //     $this->repoClub->addClub();
    // } 
    
}

?>