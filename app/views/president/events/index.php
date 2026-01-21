<?php
$events = $events ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes événements - ClubEdge</title>

    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/variables.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/reset.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/layout.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/components.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/utilities.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/responsive.css">
</head>

<body class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Mes événements</h1>
        <a class="btn btn-primary" href="events/create">Créer</a>
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
                                <th>Titre</th>
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
                                    <td><?= htmlspecialchars((string) $event['event_date'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars((string) ($event['location'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                                    <td><?= (int) ($event['participants_count'] ?? 0) ?></td>
                                    <td class="flex gap-2">
                                        <a class="btn btn-outline btn-sm" href="events/<?= (int) $event['id'] ?>/edit">Modifier</a>
                                        <a class="btn btn-outline btn-sm" href="events/<?= (int) $event['id'] ?>/participants">Participants</a>
                                        <form method="post" action="events/<?= (int) $event['id'] ?>/delete" onsubmit="return confirm('Supprimer cet événement ?');">
                                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                                            <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                                        </form>
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

