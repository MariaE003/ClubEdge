<?php
class Admin extends User{
    public function __construct($id, string $nom, string $prenom, string $email, string $password, ?string $image = null) {
        parent::__construct($id, $nom, $prenom, $email, $password, 'admin', $image);
    }

    public function creerClub(): void{
        // Logic to create a club
    }

    public function modifierClub(): void{
        // Logic to modify a club
    }

    public function supprimerClub(): void{
        // Logic to delete a club
    }

    public function gererEtudiants(): void{
        // Logic to manage users
    }
}