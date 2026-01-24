<?php

final class Event
{
    public ?int $id;
    public int $clubId;
    public string $title;
    public ?string $description;
    public DateTimeImmutable $eventDate;
    public ?string $location;
    public array $images;
    public int $createdBy;

    public function __construct(
        ?int $id,
        int $clubId,
        string $title,
        ?string $description,
        DateTimeImmutable $eventDate,
        ?string $location,
        array $images,
        int $createdBy
    ) {
        $this->id = $id;
        $this->clubId = $clubId;
        $this->title = $title;
        $this->description = $description;
        $this->eventDate = $eventDate;
        $this->location = $location;
        $this->images = $images;
        $this->createdBy = $createdBy;
    }

    public static function sanitizePayload(array $payload): array
    {
        $title = isset($payload['title']) ? trim(strip_tags((string) $payload['title'])) : '';
        $description = isset($payload['description']) ? trim(strip_tags((string) $payload['description'])) : null;
        $location = isset($payload['location']) ? trim(strip_tags((string) $payload['location'])) : null;
        $eventDateRaw = isset($payload['event_date']) ? trim((string) $payload['event_date']) : '';

        if ($description === '') {
            $description = null;
        }
        if ($location === '') {
            $location = null;
        }

        return [
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'event_date' => $eventDateRaw,
        ];
    }

    public static function validatePayload(array $payload): array
    {
        $errors = [];

        $title = $payload['title'] ?? '';
        if ($title === '') {
            $errors['title'] = "Le titre est obligatoire.";
        } elseif (mb_strlen($title) > 150) {
            $errors['title'] = "Le titre ne doit pas dépasser 150 caractères.";
        }

        $eventDateRaw = $payload['event_date'] ?? '';
        $eventDate = self::parseEventDate($eventDateRaw);
        if (!$eventDate) {
            $errors['event_date'] = "La date est invalide.";
        } else {
            $now = new DateTimeImmutable('now');
            if ($eventDate <= $now) {
                $errors['event_date'] = "La date doit être dans le futur.";
            }
        }

        $location = $payload['location'] ?? null;
        if ($location !== null && mb_strlen($location) > 150) {
            $errors['location'] = "Le lieu ne doit pas dépasser 150 caractères.";
        }

        return $errors;
    }

    public static function parseEventDate(string $value): ?DateTimeImmutable
    {
        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d\\TH:i', 'Y-m-d H:i:s', DateTimeInterface::ATOM];
        foreach ($formats as $format) {
            $dt = DateTimeImmutable::createFromFormat($format, $value);
            if ($dt instanceof DateTimeImmutable) {
                return $dt;
            }
        }

        try {
            return new DateTimeImmutable($value);
        } catch (Throwable) {
            return null;
        }
    }
}
