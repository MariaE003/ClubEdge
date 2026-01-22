<?php
$event = $event ?? [];
$errors = $errors ?? [];
$old = $old ?? [];
$images = $event['images'] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un événement - ClubEdge</title>

    <link rel="stylesheet" href="../../assets/css/variables.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/components.css">
    <link rel="stylesheet" href="../../assets/css/utilities.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
</head>

<body class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Modifier l'événement</h1>
        <a class="btn btn-outline" href="../..">Retour</a>
    </div>

    <div class="card mb-6">
        <div class="card-body">
            <p class="text-sm text-muted">Images actuelles</p>
            <?php if (!$images): ?>
                <p class="text-sm text-muted">Aucune image.</p>
            <?php else: ?>
                <div class="flex gap-3 flex-wrap">
                    <?php foreach ($images as $img): ?>
                        <img src="<?= htmlspecialchars((string) $img, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="" style="width:120px;height:80px;object-fit:cover;border-radius:6px;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="post" action="update" enctype="multipart/form-data" class="grid gap-4" novalidate>
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">

                <div class="form-group">
                    <label class="form-label" for="title">Titre</label>
                    <input class="form-input" id="title" name="title" value="<?= htmlspecialchars((string) ($old['title'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                    <?php if (!empty($errors['title'])): ?>
                        <p class="text-sm text-danger"><?= htmlspecialchars((string) $errors['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="event_date">Date</label>
                    <input class="form-input" id="event_date" name="event_date" type="datetime-local" value="<?= htmlspecialchars((string) ($old['event_date'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                    <?php if (!empty($errors['event_date'])): ?>
                        <p class="text-sm text-danger"><?= htmlspecialchars((string) $errors['event_date'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Lieu</label>
                    <input class="form-input" id="location" name="location" value="<?= htmlspecialchars((string) ($old['location'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                    <?php if (!empty($errors['location'])): ?>
                        <p class="text-sm text-danger"><?= htmlspecialchars((string) $errors['location'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea class="form-input" id="description" name="description" rows="4"><?= htmlspecialchars((string) ($old['description'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="images">Ajouter des images</label>
                    <input class="form-input" id="images" name="images[]" type="file" accept="image/*" multiple>
                    <?php if (!empty($errors['images'])): ?>
                        <p class="text-sm text-danger"><?= htmlspecialchars((string) $errors['images'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex gap-3 justify-end">
                    <button class="btn btn-primary" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

