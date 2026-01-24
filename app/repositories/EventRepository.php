<?php

final class EventRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(Event $event): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO events (club_id, title, description, event_date, location, images, created_by)
            VALUES (:club_id, :title, :description, :event_date, :location, :images::text[], :created_by)
            RETURNING id
        ");

        $stmt->execute([
            ':club_id' => $event->clubId,
            ':title' => $event->title,
            ':description' => $event->description,
            ':event_date' => $event->eventDate->format('Y-m-d H:i:s'),
            ':location' => $event->location,
            ':images' => $this->toPgTextArray($event->images),
            ':created_by' => $event->createdBy,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(int $eventId, Event $event): bool
    {
        $stmt = $this->db->prepare("
            UPDATE events
            SET title = :title,
                description = :description,
                event_date = :event_date,
                location = :location,
                images = :images::text[]
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $eventId,
            ':title' => $event->title,
            ':description' => $event->description,
            ':event_date' => $event->eventDate->format('Y-m-d H:i:s'),
            ':location' => $event->location,
            ':images' => $this->toPgTextArray($event->images),
        ]);
    }

    public function delete(int $eventId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$eventId]);
    }

    public function findById(int $eventId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT e.*,
                   c.name AS club_name,
                   (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.id) AS participants_count,
                   (e.event_date <= NOW()) AS is_past
            FROM events e
            JOIN clubs c ON c.id = e.club_id
            WHERE e.id = ?
        ");
        $stmt->execute([$eventId]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        return $this->normalizeEventRow($row);
    }

    public function listByClub(int $clubId): array
    {
        $stmt = $this->db->prepare("
            SELECT e.*,
                   (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.id) AS participants_count,
                   (e.event_date <= NOW()) AS is_past
            FROM events e
            WHERE e.club_id = ?
            ORDER BY e.event_date DESC
        ");
        $stmt->execute([$clubId]);
        $rows = $stmt->fetchAll();
        return array_map(fn ($r) => $this->normalizeEventRow($r), $rows);
    }

    public function listForStudent(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT e.*,
                   c.name AS club_name,
                   (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.id) AS participants_count,
                   (e.event_date <= NOW()) AS is_past,
                   EXISTS(
                       SELECT 1
                       FROM event_participants ep2
                       WHERE ep2.event_id = e.id AND ep2.user_id = :user_id
                   ) AS is_joined
            FROM events e
            JOIN clubs c ON c.id = e.club_id
            ORDER BY e.event_date ASC
        ");
        $stmt->execute([':user_id' => $userId]);
        $rows = $stmt->fetchAll();
        return array_map(fn ($r) => $this->normalizeEventRow($r), $rows);
    }

    public function listParticipants(int $eventId): array
    {
        $stmt = $this->db->prepare("
            SELECT u.id, u.nom, u.prenom, u.email, u.profile
            FROM event_participants ep
            JOIN users u ON u.id = ep.user_id
            WHERE ep.event_id = ?
            ORDER BY u.nom ASC, u.prenom ASC
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public function isPast(int $eventId): ?bool
    {
        $stmt = $this->db->prepare("SELECT (event_date <= NOW()) AS is_past FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        return (bool) $row['is_past'];
    }

    public function signup(EventSignup $signup): array
    {
        $errors = $signup->validate();
        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $isPast = $this->isPast($signup->eventId);
        if ($isPast === null) {
            return ['success' => false, 'message' => "Événement introuvable."];
        }
        if ($isPast) {
            return ['success' => false, 'message' => "Impossible de s'inscrire à un événement passé."];
        }

        if ($this->isUserSignedUp($signup->eventId, $signup->userId)) {
            return ['success' => true, 'message' => "Déjà inscrit."];
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO event_participants (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$signup->eventId, $signup->userId]);
        } catch (PDOException $e) {
            if (($e->getCode() ?? '') === '23505') {
                return ['success' => true, 'message' => "Déjà inscrit."];
            }
            throw $e;
        }

        return ['success' => true, 'message' => "Inscription réussie."];
    }

    public function isUserSignedUp(int $eventId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM event_participants WHERE event_id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$eventId, $userId]);
        return (bool) $stmt->fetchColumn();
    }

    public function eventBelongsToPresident(int $eventId, int $presidentId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM events e
            JOIN clubs c ON c.id = e.club_id
            WHERE e.id = ? AND c.president_id = ?
            LIMIT 1
        ");
        $stmt->execute([$eventId, $presidentId]);
        return (bool) $stmt->fetchColumn();
    }

    private function toPgTextArray(array $values): string
    {
        if (!$values) {
            return '{}';
        }
        $encoded = array_map(function ($v) {
            $v = (string) $v;
            $v = str_replace(['\\', '"'], ['\\\\', '\\"'], $v);
            return '"' . $v . '"';
        }, $values);
        return '{' . implode(',', $encoded) . '}';
    }

    private function fromPgTextArray(?string $value): array
    {
        if ($value === null) {
            return [];
        }

        $trimmed = trim($value);
        if ($trimmed === '' || $trimmed === '{}') {
            return [];
        }

        $trimmed = trim($trimmed, '{}');
        if ($trimmed === '') {
            return [];
        }

        return str_getcsv($trimmed, ',', '"', '\\');
    }

    private function normalizeEventRow(array $row): array
    {
        if (array_key_exists('images', $row)) {
            $row['images'] = $this->fromPgTextArray($row['images']);
        } else {
            $row['images'] = [];
        }
        if (array_key_exists('is_joined', $row)) {
            $row['is_joined'] = (bool) $row['is_joined'];
        }
        if (array_key_exists('is_past', $row)) {
            $row['is_past'] = (bool) $row['is_past'];
        }
        if (array_key_exists('participants_count', $row)) {
            $row['participants_count'] = (int) $row['participants_count'];
        }
        return $row;
    }
    public function listAllForAdmin(array $filters = []): array
{
    $q = trim((string)($filters['q'] ?? ''));
    $clubId = $filters['club_id'] ?? null;
    $status = (string)($filters['status'] ?? '');

    $sql = "
        SELECT e.*,
               c.name AS club_name,
               u.nom AS creator_nom,
               u.prenom AS creator_prenom,
               (SELECT COUNT(*) FROM event_participants ep WHERE ep.event_id = e.id) AS participants_count,
               (e.event_date <= NOW()) AS is_past
        FROM events e
        JOIN clubs c ON c.id = e.club_id
        LEFT JOIN users u ON u.id = e.created_by
        WHERE 1=1
    ";

    $params = [];

    if ($q !== '') {
        $sql .= " AND (e.title ILIKE :q OR COALESCE(e.description,'') ILIKE :q) ";
        $params[':q'] = '%' . $q . '%';
    }

    if ($clubId !== null && $clubId !== '') {
        $sql .= " AND e.club_id = :club_id ";
        $params[':club_id'] = (int)$clubId;
    }

    if ($status === 'upcoming') {
        $sql .= " AND e.event_date > NOW() ";
    } elseif ($status === 'past') {
        $sql .= " AND e.event_date <= NOW() ";
    }

    $sql .= " ORDER BY e.event_date DESC ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll();
    return array_map(fn($r) => $this->normalizeEventRow($r), $rows);
}

}
