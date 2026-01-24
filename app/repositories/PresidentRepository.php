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

    public function getClubByPresidentId(int $userId): ?int
    {
        $stmt = $this->db->prepare("
            SELECT id 
            FROM clubs 
            WHERE president_id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : null;
    }

    public function countMembers(int $clubId): int
    {
        $stmt = $this->db->prepare("
            SELECT members 
            FROM clubs 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $clubId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !is_array($row['members'])) {
            return 0;
        }

        // On filtre les valeurs null / non numériques qui pourraient venir d'un mauvais INSERT
        $validIds = array_filter($row['members'], 'is_int');
        return count($validIds);
    }

    public function countEvents(int $clubId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM events 
            WHERE club_id = :id
        ");
        $stmt->execute(['id' => $clubId]);
        return (int) $stmt->fetchColumn();
    }

    public function countArticles(int $clubId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM articles 
            WHERE club_id = :id
        ");
        $stmt->execute(['id' => $clubId]);
        return (int) $stmt->fetchColumn();
    }

    public function getUpcomingEvents(int $clubId): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.title, 
                e.location, 
                e.event_date,
                (SELECT COUNT(*) 
                 FROM event_participants ep 
                 WHERE ep.event_id = e.id) AS participants
            FROM events e
            WHERE e.club_id = :id 
              AND e.event_date >= CURRENT_DATE
            ORDER BY e.event_date ASC
            LIMIT 3
        ");
        $stmt->execute(['id' => $clubId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getMembers(int $clubId): array
    {
        $stmt = $this->db->prepare("
            SELECT members 
            FROM clubs 
            WHERE id = :id
        ");
        $stmt->execute(['id' => $clubId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['members']) || !is_array($row['members'])) {
            return [];
        }

        // On enlève les éventuelles valeurs invalides
        $memberIds = array_filter($row['members'], 'is_int');
        if (empty($memberIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($memberIds), '?'));
        
        $stmt = $this->db->prepare("
            SELECT id, prenom, nom
            FROM users
            WHERE id IN ($placeholders)
            ORDER BY nom, prenom
        ");
        
        $stmt->execute($memberIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}