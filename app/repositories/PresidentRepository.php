<?php

class PresidentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPresidentByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT prenom, nom 
            FROM users 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

     public function getDashboardData(int $userId): array
    {

        // Étudiant
        $stmt = $this->db->prepare("SELECT id, prenom, nom, email FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Club via members[]
        $stmt = $this->db->prepare("
            SELECT c.id, c.name, c.members
            FROM clubs c
            WHERE :uid = ANY(c.members)
        ");
        $stmt->execute(['uid' => $userId]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);

        // ❌ Pas de club
        if (!$club) {
            return [
                'hasClub' => false,
                'student' => [
                    'prenom' => $student['prenom'],
                    'nom' => $student['nom'],
                    'email' => $student['email'],
                    'initials' => strtoupper($student['prenom'][0] . $student['nom'][0])
                ],
                'stats' => [
                    'clubs' => 0,
                    'events' => 0,
                    'reviews' => 0,
                    'participations' => 0
                ],
                'club' => null,
                'members' => [],
                'upcoming_events' => []
            ];
        }

        $clubId = $club['id'];

        // Membres
        $members = $this->db->query("
            SELECT prenom, nom
            FROM users
            WHERE id = ANY(
                SELECT unnest(members) FROM clubs WHERE id = $clubId
            )
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Événements à venir
        $events = $this->db->query("
            SELECT title, event_date, location
            FROM events
            WHERE club_id = $clubId AND event_date >= NOW()
            ORDER BY event_date ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'hasClub' => true,

            'student' => [
                'prenom' => $student['prenom'],
                'nom' => $student['nom'],
                'email' => $student['email'],
                'initials' => strtoupper($student['prenom'][0] . $student['nom'][0])
            ],

            'club' => [
                'id' => $clubId,
                'name' => $club['name'],
                'members_count' => count($members),
                'max_members' => 8
            ],

            'members' => $members,
            'upcoming_events' => $events,

            'stats' => [
                'clubs' => 1,
                'events' => count($events),
                'reviews' => 5,
                'participations' => 8
            ]
        ];
    }
}