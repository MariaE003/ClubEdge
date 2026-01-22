<?php

final class EventSignup
{
    public int $eventId;
    public int $userId;

    public function __construct(int $eventId, int $userId)
    {
        $this->eventId = $eventId;
        $this->userId = $userId;
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->eventId <= 0) {
            $errors['event_id'] = "Événement invalide.";
        }
        if ($this->userId <= 0) {
            $errors['user_id'] = "Utilisateur invalide.";
        }
        return $errors;
    }
}