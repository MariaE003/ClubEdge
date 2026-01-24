<?php
$events = $events ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - ClubEdge</title>

    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/utilities.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>

<body class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Événements</h1>
        <a class="btn btn-outline" href="dashboard">Dashboard</a>
    </div>

    <?php if (!empty($flash['message'])): ?>
        <div class="mb-4 <?= ($flash['type'] ?? '') === 'success' ? 'alert alert-success' : 'alert alert-error' ?>">
            <?= htmlspecialchars((string) $flash['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (!$events): ?>
                <p class="text-sm text-muted">Aucun événement.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Événement</th>
                                <th>Club</th>
                                <th>Date</th>
                                <th>Lieu</th>
                                <th>Participants</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) $event['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($event['club_name'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) $event['event_date'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($event['location'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= (int) ($event['participants_count'] ?? 0) ?></td>
                                    <td>
                                        <a class="btn btn-outline btn-sm" href="events/<?= (int) $event['id'] ?>">Détails</a>
                                        <a class="btn btn-outline btn-sm" href="review/<?= (int) $event['id'] ?>">Review</a>
                                    </td>
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
