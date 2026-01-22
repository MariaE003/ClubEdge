<?php
class ClubController extends BaseController{

    private ClubRepository $repoClub;

    public function __construct(){
        parent::__construct();
        $pdo=Database::getInstance()->getConnection();
        $this->repoClub=new ClubRepository($pdo);
    }


    // formulaire d'ajou
    public function PageAdd(){
        $this->render('admin/create-club.twig', [
                'error' => $error ?? null
            ]);
    }
    public function AddClub(){
        $error='';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // echo 'hi';
                // checker le nbr des club creer par admin
                $totalClub = $this->repoClub->countClubs();
                if ($totalClub >= 6) {
                    throw new Exception("impossible d'ajouter un club : le nombre maximal de clubs est 6.");
                }
                // if ($totalClub < 4) {
                //     throw new Exception("Vous devez avoir au moins 4 clubs avant d'ajouter un nouveau.");
                // }
                $club = new Club( null,$_POST['nom'],$_POST['description'] ?? null, null,$_POST['logo']?? null,[]);
                $this->repoClub->addClub($club);
                header('Location: /ClubEdge/admin/clubs');
                exit;

            } catch (Exception $e) {
                $this->render('admin/create-club.twig', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->render('admin/create-club.twig', [
            'error' => $error
        ]);
}
    // page update club
    public function pageUpdateClubs()
    {
        if (!isset($_GET['id'])) {
            throw new Exception("ID du club manquant");
        }
        $id = (int) $_GET['id'];

        // search club
        $clubData = $this->repoClub->findClubById($id);
        if (!$clubData) {
            throw new Exception("Club introuvable");
        }
        
        //si formulaire soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                $club = new Club(
                    $id,
                    $_POST['nom'],
                    $_POST['description'] ?? null,
                    $clubData['president_id'], //idPresedent
                    $_POST['logo'] ?? null,
                    $_POST['members'] ?? [],
                    $clubData['created_at']
                );
                $this->repoClub->updateClub($club);
                // redirection après succès
                header('Location: /ClubEdge/admin/clubs');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        // afficher la vue
        $this->render('admin/edit-club.twig', [
            'club'  => $clubData,
            'error' => $error ?? null
        ]);
    }
    // supp club
    public function deleteClub(){
        if (!isset($_GET['id'])) {
            throw new Exception("ID du club manquant");
        }
            $id = (int) $_GET['id'];
        try {
            $this->repoClub->removeClub($id);
            header('Location: /ClubEdge/admin/clubs');
            exit;
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    // pour affichage des clubs
    public function AfficherClub(){
        $clubs=$this->repoClub->allClubs();
        echo $this->render('student/clubs-list.twig',[
            'clubs'=>$clubs,
        ]);
    }
    // pour affichage des clubs pour Admin
    public function AfficherClubAdmin(){
        $clubs=$this->repoClub->allClubs();
        // var_dump($clubs);
        // var_dump($_SESSION['user']);
        echo $this->render('admin/clubs.twig',[
            'clubs'=>$clubs,
            'user'  => $_SESSION['user'] ?? null,
        ]);
    }
    // detai dun club
    public function detailClub(){
        if (!isset($_GET['idC'])) {
            die('club introvalbe !');
        }
        $idClub=(int)$_GET['idC'];
        $club=$this->repoClub->findClubById($idClub);
        // var_dump($club['logo']);
        $NameClub=strtoupper(substr($club['name'],0,2));
        echo $this->render('student/club-details.twig',[
            'club'=>$club,
            'NameClub'=>$NameClub,
        ]);
        
    }
    // searsh
    public function searchByName(){
        $clubR=$this->repoClub->searchClubByName($_POST["name"]);
    }

    // les evnet des club
    public function listEventByClub(int $clubId) {
        $events = $this->eventRepo->findEventByClub($clubId);

        $this->render('events/list', [
            'events' => $events,
            'clubId' => $clubId
        ]);
    }

    
}

?>