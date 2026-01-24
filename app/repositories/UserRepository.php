<?php
class UserRepository {

    private PDO $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }


    public function searchPaginated(
        int $limit,
        int $offset,
        ?string $q = null
    ): array {

        $sql = "SELECT * FROM users WHERE role IN ('etudiant','president')";
        if ($q) {
            $sql .= " AND (nom ILIKE :q OR prenom ILIKE :q OR email ILIKE :q)";
        }
        $sql .= " ORDER BY date_creation DESC LIMIT :l OFFSET :o";

        $stmt = $this->db->prepare($sql);
        if ($q) $stmt->bindValue(':q', "%$q%");
        $stmt->bindValue(':l', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $users = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $users[] = UserFactory::create($row); // 👈 ICI
        }

        return $users;
    }

    public function countSearch(?string $q = null): int {
        $sql = "SELECT COUNT(*) FROM users WHERE role IN ('etudiant','president')";
        if ($q) {
            $sql .= " AND (nom ILIKE :q OR prenom ILIKE :q OR email ILIKE :q)";
        }
        $stmt = $this->db->prepare($sql);
        if ($q) $stmt->bindValue(':q', "%$q%");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }


    public function update(User $user): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET nom=?, prenom=?, email=?, profile=? WHERE id=?"
        );
        return $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getEmail(),
            $user->getImage(),
            $user->getId()
        ]);
    }

    /* ===== DELETE ===== */

    public function delete(User $user): bool {
        return $this->deleteById($user->getId());
    }

    public function deleteById(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function updateInfo(array $data){
        $stmt = $this->db->prepare("update users set nom = ? , prenom = ? , email = ? , profile = ? where id =  ? ");
        return $stmt->execute([$data["nom"] , $data["prenom"], $data["email"],$data["profile"] , $data["id"]]);
    }
    public function searchForAdmin(array $filters = []): array
{
    $q    = trim($filters['q'] ?? '');
    $role = $filters['role'] ?? '';

    $sql = "
        SELECT u.*
        FROM users u
        WHERE 1=1
    ";

    $params = [];

    if ($q !== '') {
        $sql .= " AND (
            u.nom ILIKE :q
            OR u.prenom ILIKE :q
            OR u.email ILIKE :q
        )";
        $params[':q'] = '%' . $q . '%';
    }

    if ($role !== '') {
        $sql .= " AND u.role = :role";
        $params[':role'] = $role;
    }

    $sql .= " ORDER BY u.date_creation DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

}
