<?php
$event = $event ?? [];
$images = $event['images'] ?? [];
$isJoined = (bool) ($event['is_joined'] ?? false);
$isPast = (bool) ($event['is_past'] ?? false);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails événement - ClubEdge</title>

    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/variables.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/reset.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/layout.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/components.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/utilities.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/responsive.css">
</head>

<body class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold"><?= htmlspecialchars((string) ($event['title'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
        <a class="btn btn-outline" href="..">Retour</a>
    </div>

    <?php if (!empty($flash['message'])): ?>
        <div class="mb-4 <?= ($flash['type'] ?? '') === 'success' ? 'alert alert-success' : 'alert alert-error' ?>">
            <?= htmlspecialchars((string) $flash['message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card mb-6">
        <div class="card-body grid gap-2">
            <p><strong>Club:</strong> <?= htmlspecialchars((string) ($event['club_name'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars((string) ($event['event_date'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p><strong>Lieu:</strong> <?= htmlspecialchars((string) ($event['location'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <p><strong>Participants:</strong> <?= (int) ($event['participants_count'] ?? 0) ?></p>
            <?php if (!empty($event['description'])): ?>
                <p><strong>Description:</strong> <?= htmlspecialchars((string) $event['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($images): ?>
        <div class="card mb-6">
            <div class="card-body">
                <p class="text-sm text-muted mb-3">Images</p>
                <div class="flex gap-3 flex-wrap">
                    <?php foreach ($images as $img): ?>
                        <img src="<?= htmlspecialchars((string) $img, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="" style="width:160px;height:100px;object-fit:cover;border-radius:6px;">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex gap-3">
        <?php if ($isPast): ?>
            <span class="badge badge-secondary">Événement passé</span>
        <?php elseif ($isJoined): ?>
            <span class="badge badge-accent">Déjà inscrit</span>
        <?php else: ?>
            <form method="post" action="<?= (int) ($event['id'] ?? 0) ?>/join">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                <button class="btn btn-primary" type="submit">S'inscrire</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
