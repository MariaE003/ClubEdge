<?php 
class AuthRepository{



public function login(string $email, string $password): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return [
                "success" => false,
                "message" => "Email ou mot de passe incorrect"
            ];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        return [
            "success" => true,
            "message" => "Connexion réussie",
            "user" => $user
        ];
}
public function register(Register $register){
    return $register->register();

}

}