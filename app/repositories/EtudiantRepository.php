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
    public function getDashboardData(int $userId): array
    {
        /**
         * Étudiant + club + président
         */
        $stmt = $this->db->prepare("
            SELECT 
                u.id,
                u.prenom,
                u.nom,
                u.email,

                c.id AS club_id,
                c.name AS club_name,

                p.prenom AS pres_prenom,
                p.nom AS pres_nom

            FROM users u
            LEFT JOIN clubs c ON u.id = ANY(c.members)
            LEFT JOIN users p ON p.id = c.president_id
            WHERE u.id = :user_id
        ");
        $stmt->execute([':user_id' => $userId]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        // Aucun club
        if (empty($u['club_id'])) {
            return $this->emptyDashboard();
        }

        $clubId = (int)$u['club_id'];

        return [
            // ⚠️ on garde le nom "president" pour le template
            'president' => [
                'prenom'   => $u['pres_prenom'] ?? '—',
                'fullname' => ($u['pres_prenom'] && $u['pres_nom'])
                    ? $u['pres_prenom'].' '.$u['pres_nom']
                    : 'Aucun président',
                'initials' => ($u['pres_prenom'] && $u['pres_nom'])
                    ? strtoupper($u['pres_prenom'][0].$u['pres_nom'][0])
                    : '--'
            ],

            'stats' => [
                'members'     => $this->countMembers($clubId),
                'max_members' => 8,
                'events'      => $this->countEvents($clubId),
                'articles'    => $this->countArticles($clubId)
            ],

            'upcoming_events' => $this->getUpcomingEvents($clubId),
            'members'         => $this->getMembers($clubId),
            'average_rating'  => $this->getAverageRating($clubId)
        ];
    }

    /* ===================== */
    /* ===== HELPERS ======= */
    /* ===================== */

    private function emptyDashboard(): array
    {
        return [
            'president' => [
                'prenom'   => '—',
                'fullname' => 'Aucun club',
                'initials' => '--'
            ],
            'stats' => [
                'members'     => 0,
                'max_members' => 8,
                'events'      => 0,
                'articles'    => 0
            ],
            'upcoming_events' => [],
            'members'         => [],
            'average_rating'  => null
        ];
    }

    private function countMembers(int $clubId): int
    {
        return (int)$this->db
            ->query("SELECT cardinality(members) FROM clubs WHERE id = {$clubId}")
            ->fetchColumn();
    }

    private function countEvents(int $clubId): int
    {
        return (int)$this->db
            ->query("SELECT COUNT(*) FROM events WHERE club_id = {$clubId}")
            ->fetchColumn();
    }

    private function countArticles(int $clubId): int
    {
        return (int)$this->db
            ->query("SELECT COUNT(*) FROM articles WHERE club_id = {$clubId}")
            ->fetchColumn();
    }

    private function getUpcomingEvents(int $clubId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.id,
                e.title,
                e.location,
                e.event_date,
                (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.id) AS participants
            FROM events e
            WHERE e.club_id = :club_id
              AND e.event_date >= NOW()
            ORDER BY e.event_date ASC
            LIMIT 5
        ");
        $stmt->execute([':club_id' => $clubId]);

        $events = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $e) {
            $events[] = [
                'id'           => $e['id'],
                'title'        => $e['title'],
                'location'     => $e['location'],
                'participants' => $e['participants'],
                'day'          => date('d', strtotime($e['event_date'])),
                'month'        => date('M', strtotime($e['event_date']))
            ];
        }

        return $events;
    }

    private function getMembers(int $clubId): array
    {
        $stmt = $this->db->prepare("
            SELECT u.prenom, u.nom, u.role
            FROM users u
            JOIN clubs c ON u.id = ANY(c.members)
            WHERE c.id = :club_id
            ORDER BY u.role DESC, u.nom
        ");
        $stmt->execute([':club_id' => $clubId]);

        $members = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $m) {
            $members[] = [
                'name'         => $m['prenom'].' '.$m['nom'],
                'initials'     => strtoupper($m['prenom'][0].$m['nom'][0]),
                'is_president' => $m['role'] === 'president'
            ];
        }

        return $members;
    }

    private function getAverageRating(int $clubId): ?float
    {
        $stmt = $this->db->prepare("
            SELECT AVG(r.rating)
            FROM reviews r
            JOIN events e ON e.id = r.event_id
            WHERE e.club_id = :club_id
        ");
        $stmt->execute([':club_id' => $clubId]);

        return round($stmt->fetchColumn(), 1) ?: null;
    }
}