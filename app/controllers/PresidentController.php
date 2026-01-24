<?php

class PresidentController extends BaseController
{
    private PresidentRepository $repo;

    public function __construct()
    {
        parent::__construct();
        $this->repo = new PresidentRepository();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /loginPage'); // ← adapte selon ton routing
            exit;
        }

        $userId = (int) $_SESSION['user_id'];

        // Récupération des infos du président
        $president = $this->repo->getPresidentByUserId($userId);
        if (!$president) {
            // Cas rare : compte supprimé entre-temps
            session_destroy();
            header('Location: /loginPage');
            exit;
        }

        // Récupération du club
        $clubId = $this->repo->getClubByPresidentId($userId);
        if (!$clubId) {
            // Pas de club → tu peux rediriger ou afficher un message
            // Pour l'instant on rend la vue avec des valeurs à 0
            $clubId = 0;
        }

        $membersCount  = $this->repo->countMembers($clubId);
        $eventsCount   = $this->repo->countEvents($clubId);
        $articlesCount = $this->repo->countArticles($clubId);

        $events  = $this->repo->getUpcomingEvents($clubId);
        $members = $this->repo->getMembers($clubId);

        $this->render('president/dashboard.twig', [
            'president' => [
                'prenom'   => $president['prenom'],
                'nom'      => $president['nom'],
                'fullname' => trim($president['prenom'] . ' ' . $president['nom']),
                'initials' => strtoupper(mb_substr($president['prenom'] ?? '', 0, 1) . mb_substr($president['nom'] ?? '', 0, 1))
            ],
            'stats' => [
                'members'     => $membersCount,
                'max_members' => 20,           // valeur fixe comme dans ton HTML
                'events'      => $eventsCount,
                'articles'    => $articlesCount
            ],
            'upcoming_events' => array_map(function ($e) {
                $date = strtotime($e['event_date']);
                return [
                    'day'         => date('d', $date),
                    'month'       => date('M', $date),
                    'title'       => $e['title'],
                    'location'    => $e['location'] ?: 'Lieu non précisé',
                    'participants'=> (int)$e['participants']
                ];
            }, $events),

            'members' => array_map(function ($m) {
                return [
                    'name'    => trim($m['prenom'] . ' ' . $m['nom']),
                    'initials'=> strtoupper(mb_substr($m['prenom'] ?? '', 0, 1) . mb_substr($m['nom'] ?? '', 0, 1))
                ];
            }, $members)
        ]);
    }
}   