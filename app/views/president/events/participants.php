<?php
$event = $event ?? [];
$participants = $participants ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - ClubEdge</title>

    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/variables.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/reset.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/layout.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/components.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/utilities.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/responsive.css">
</head>

<body class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">
            Participants — <?= htmlspecialchars((string) ($event['title'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </h1>
        <a class="btn btn-outline" href="../..">Retour</a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (!$participants): ?>
                <p class="text-sm text-muted">Aucun participant.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participants as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) ($p['nom'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($p['prenom'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($p['email'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

