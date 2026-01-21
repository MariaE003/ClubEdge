<?php
class UserFactory {

    public static function create(array $data): User {
        return match ($data['role']) {
            'etudiant' => new Etudiant(
                $data['id'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['password'],
                $data['profile'] ?? null,
                $data['date_creation']
            ),
            'president' => new President(
                $data['id'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['password'],
                $data['profile'] ?? null,
                $data['date_creation']
            ),
            default => throw new Exception("Role inconnu")
        };
    }
}
