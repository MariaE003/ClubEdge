<?php
class ClubFactory{
    public static function fromDbRow( $row): Club
    {
        $id          = isset($row['id']) ? (int)$row['id'] : null;
        $nom         = $row['name'] ?? $row['name'] ?? 'non assigne';
        $description = $row['description'] ?? null;
        $presidentId = isset($row['president_id']) ? (int)$row['president_id'] : null;
        $logo        = $row['logo'] ?? null;
        $createdAt   = $row['created_at'] ?? null;

        $membersRaw = $row['members'] ?? null;
        $members = self::normalizeMembers($membersRaw);

        return new Club(
            $id,
            $nom,
            $description,
            $presidentId,
            $logo,
            $members,
            $createdAt
        );
    }
    private static function normalizeMembers(mixed $membersRaw): array
    {
        if ($membersRaw === null) {
            return [];
        }

        if (is_array($membersRaw)) {
            return array_map('intval', $membersRaw);
        }

        if (is_string($membersRaw)) {
            $s = trim($membersRaw);

            if ($s === '{}' || $s === '') {
                return [];
            }

            if ($s[0] === '{' && substr($s, -1) === '}') {
                $s = substr($s, 1, -1);
            }

            if (trim($s) === '') {
                return [];
            }

            $parts = explode(',', $s);
            return array_map('intval', $parts);
        }

        return [];
    }

}