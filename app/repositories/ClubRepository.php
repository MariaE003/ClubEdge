<?php

final class ClubRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findIdByPresidentId(int $presidentId): ?int
    {
        $stmt = $this->db->prepare("SELECT id FROM clubs WHERE president_id = ? LIMIT 1");
        $stmt->execute([$presidentId]);
        $id = $stmt->fetchColumn();
        if ($id === false) {
            return null;
        }
        return (int) $id;
    }
}
