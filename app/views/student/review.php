<?php
require_once __DIR__ . '/../../config/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Review Événement - ClubEdge</title>

  <!-- ClubEdge CSS -->
  <link rel="stylesheet" href="/assets/css/variables.css">
  <link rel="stylesheet" href="/assets/css/reset.css">
  <link rel="stylesheet" href="/assets/css/layout.css">
  <link rel="stylesheet" href="/assets/css/components.css">
  <link rel="stylesheet" href="/assets/css/utilities.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
  <style>
    /* ⭐ Rating stars */
    .stars {
      display: inline-flex;
      flex-direction: row-reverse;
      gap: var(--space-1);
    }
    .stars input { display: none; }
    .stars label {
      cursor: pointer;
      font-size: 1.4rem;
      color: var(--color-gray-300);
      transition: color var(--duration-fast), transform var(--duration-fast);
    }
    .stars label:hover {
      transform: translateY(-1px);
    }
    .stars input:checked ~ label,
    .stars label:hover,
    .stars label:hover ~ label {
      color: var(--color-accent);
    }

    .badge {
      display: inline-flex;
      gap: var(--space-2);
      padding: var(--space-2) var(--space-3);
      border: 1px solid var(--border-color);
      border-radius: var(--radius-full);
      background: var(--color-bg-secondary);
      font-size: var(--text-xs);
      color: var(--color-text-secondary);
    }

    .muted { color: var(--color-text-muted); }

    .grid-2 {
      display: grid;
      grid-template-columns: 1.2fr 0.8fr;
      gap: var(--space-6);
    }

    @media (max-width: 900px) {
      .grid-2 { grid-template-columns: 1fr; }
    }

    textarea {
      min-height: 120px;
      resize: vertical;
    }
  </style>
</head>

<body class="p-6">

  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-xl font-semibold">Review Événement</h1>
      <p class="text-sm muted">Donne une note et un commentaire</p>
    </div>
    <div class="flex gap-2">
      <a href="events.html" class="btn btn-outline">← Retour</a>
      <a href="dashboard.html" class="btn btn-outline">Dashboard</a>
    </div>
  </div>

  <div class="grid-2">

    <!-- LEFT: Event + Reviews -->
    <div>

      <!-- Event info -->
      <div class="card mb-6">
        <div class="card-body">
          <h2 class="text-lg font-semibold">Hackathon YouCode 2026</h2>

          <div class="mt-3 flex flex-wrap gap-2">
            <span class="badge">Club : <strong>YouCode Club</strong></span>
            <span class="badge">Date : <strong>24 Mars 2026</strong></span>
            <span class="badge">Lieu : <strong>Safi</strong></span>
          </div>
        </div>
      </div>

      <!-- Reviews list -->
      <div class="card">
        <div class="card-body">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold">Reviews</h3>
            <span class="text-sm muted">2 avis</span>
          </div>

          <div class="table-wrapper">
            <table class="table w-full">
              <thead>
                <tr>
                  <th>Note</th>
                  <th>Commentaire</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>5 / 5</strong></td>
                  <td>Organisation parfaite et bonne ambiance 🔥</td>
                  <td class="muted">20/03/2026</td>
                </tr>
                <tr>
                  <td><strong>4 / 5</strong></td>
                  <td>Très bon événement mais un peu long</td>
                  <td class="muted">21/03/2026</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- RIGHT: Add review -->
    <div>

      <div class="card">
        <div class="card-body">
          <h3 class="text-base font-semibold mb-2">Ajouter un review</h3>
          <p class="text-sm muted mb-4">Ton feedback aide le club à s’améliorer</p>

          <form action="/review" method="POST">
            <!-- Rating -->
            <div class="mb-4">
              <label class="text-sm font-medium">Note</label>
              <div class="mt-2 stars">
                <input type="radio" id="r5" name="rating" value="5">
                <label for="r5">★</label>

                <input type="radio" id="r4" name="rating" value="4">
                <label for="r4">★</label>

                <input type="radio" id="r3" name="rating" value="3">
                <label for="r3">★</label>

                <input type="radio" id="r2" name="rating" value="2">
                <label for="r2">★</label>

                <input type="radio" id="r1" name="rating" value="1">
                <label for="r1">★</label>
              </div>
            </div>

            <!-- Comment -->
            <div class="mb-4">
              <label class="text-sm font-medium">Commentaire</label>
              <textarea
                class="input w-full mt-2"
                placeholder="Ex : Bonne organisation, speakers intéressants..."
                name="comment"
              ></textarea>
            </div>

            <div class="flex gap-2">
              <button type="submit" name="submit" class="btn">Publier</button>
              <button type="reset" class="btn btn-outline">Annuler</button>
            </div>
          </form>

        </div>
      </div>

      <!-- Tips -->
      <div class="card mt-6">
        <div class="card-body">
          <h4 class="text-sm font-semibold mb-2">Conseils</h4>
          <ul class="text-sm muted" style="display:grid; gap:8px;">
            <li>✅ Sois constructif</li>
            <li>✅ Parle de l’organisation et du contenu</li>
            <li>❌ Pas d’insultes</li>
          </ul>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
