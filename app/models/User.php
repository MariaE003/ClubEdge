<?php 

abstract class User{
    protected ?int $id = null;
    protected string $nom;
    protected string $prenom;
    protected string $email;
    protected string $password;
    protected string $role;
    protected string $image;
    protected ?string $dateC;

    protected PDO $db;

    public function __construct($id , string $nom,string $prenom,string $email,string $password,string $role,?string $image = null , $dateC = null ) {
        $this->db =  Database::getInstance()->getConnection();
        $this->id = $id ;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
        $this->image = $image;
        $this->dateC = $dateC;
    }
    public function __get($name) {
        return $this->$name;
    }
    public function __set($name,$value){
        $this->$name = $value;
    }
    public function getId(): ?int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function getEmail(): string {
        return $this->email;
    }


    public function getRole(): string {
        return $this->role;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }


    public static function lougoutUser(){
        session_destroy();
    }
    public function updateInfoUser(): bool {
        $stmt = $this->db->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, img_utilisateur = ? WHERE id_utilisateur = ?");
        return $stmt->execute([$this->nom, $this->prenom, $this->email, $this->image, $this->id]);
    }

    public function deleteUser(): bool {
        $stmt = $this->db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
        return $stmt->execute([$this->id]);
    }

}