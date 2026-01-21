<?php
class Register extends User{

    public function __construct($id, string $nom, string $prenom, string $email, string $password, string $role, string|null $image = null){
        parent::__construct($id,$nom , $prenom , $email , $password , $role , $image , null);
    }

    public function register(): array {
        $check = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$this->email]);

        if ($check->fetch()) {
            return [
                "success" => false,
                "message" => "Email déjà utilisé"
            ];
        }
        $stmt = $this->db->prepare("
            INSERT INTO users (nom, prenom, email, password, role, profile, date_creation)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
            RETURNING id
        ");

        $stmt->execute([
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password,
            $this->role,
            $this->image
        ]);

        $this->id = $stmt->fetchColumn();

        return [
            "success" => true,
            "message" => "Inscription réussie",
            "id_utilisateur" => $this->id
        ];
    }


}