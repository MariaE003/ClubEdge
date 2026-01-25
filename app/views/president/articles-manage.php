<?php

// $idC=$_GET['idC'] ?? null;

$db = Database::getInstance()->getConnection(); // ta connexion PDO ou mysqli
$clubRepo = new ClubRepository($db);

$userId = $_SESSION['user_id']; 
// $clubRepo = new ClubRepository();
$club = $clubRepo->ClubDuPrisident($userId); 
$idClub = $club['id'] ?? null;
// var_dump($idClub);
if (!$idClub) {
    die("Erreur : aucun club sélectionné !");
}

$artic=new ArticleController();
$articles=$artic->manageArticlesByPresident(); 
// var_dump($art);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestion des articles - Espace Président">
    <title>Mes articles - Espace Président</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/variables.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/reset.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/layout.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/components.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/utilities.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/animations.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/responsive.css">
    <link rel="stylesheet" href="/../ClubEdge/public/assets/css/pages/dashboard.css">
</head>

<body>
    <div class="app-layout">
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="/" class="sidebar-logo">
                    <svg width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="4" fill="#FF4F00" />
                        <path
                            d="M12 28V12H20C22.1217 12 24.1566 12.8429 25.6569 14.3431C27.1571 15.8434 28 17.8783 28 20C28 22.1217 27.1571 24.1566 25.6569 25.6569C24.1566 27.1571 22.1217 28 20 28H12Z"
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="sidebar-logo-text">ClubEdge</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <ul class="sidebar-menu">
                    <li class="sidebar-item">
                        <a href="/ClubEdge/president/dashboard" class="sidebar-link active">
                            <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="7" height="9" rx="1" />
                                <rect x="14" y="3" width="7" height="5" rx="1" />
                                <rect x="14" y="12" width="7" height="9" rx="1" />
                                <rect x="3" y="16" width="7" height="5" rx="1" />
                            </svg>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-section">
                    {# <h3 class="sidebar-section-title">Mon Club</h3> #}
                    <ul class="sidebar-menu">
                        <li class="sidebar-item">
                            <a href="/ClubEdge/student/club-detail?idC={{id_club}}" class="sidebar-link">
                                {# <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06" />
                                </svg> #}
                                {# <span>Paramètres du club</span> #}
                                Mon Club
                            </a>
                        </li>
                        {# <li class="sidebar-item">
                            <a href="/ClubEdge/president/CreateArticle?idC={{president.club_id}}" class="sidebar-link">
                             Creer un article
                            </a>
                        </li> #}
                         <li class="sidebar-item">
                            <a href="/ClubEdge/president/ArticleManage?idC={{president.club_id}}" class="sidebar-link">
                             Mes Article
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="/ClubEdge/president/events" class="sidebar-link">
                             Mes Evenements
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="avatar avatar-sm">MM</div>
                    <div class="sidebar-user-info">
                        <span class="sidebar-user-name">Marie Martin</span>
                        <span class="sidebar-user-role">Présidente</span>
                    </div>
                </div>
            </div>
        </aside>

        <div class="app-main">
            <header class="app-header">
                <button class="btn btn-icon btn-ghost lg:hidden" id="menu-toggle" aria-label="Ouvrir le menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
                <span class="badge badge-accent ml-4">Espace Président</span>
                <div class="flex-1"></div>
                <div class="avatar avatar-sm">MM</div>
            </header>

            <main class="app-content">
                <div class="page-header flex items-center justify-between">
                    <div>
                        <h1 class="page-title">Mes articles</h1>
                        <p class="page-description">Publiez des articles sur les événements passés (texte + images).</p>
                    </div>
                    <a href="CreateArticle" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Rédiger un article
                    </a>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Événement lié</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($articles)) : ?>
                                    <?php foreach ($articles as $article) : ?>
                                        <tr>
                                            <td>
                                                <div class="font-medium"><?= htmlspecialchars($article['title']) ?></div>
                                                <div class="text-xs text-muted"><?= htmlspecialchars($article['content']) ?></div>
                                            </td>
                                            <td><?= htmlspecialchars($article['event_title'] ?? '-') ?></td>
                                            <td><?= (new DateTime($article['created_at']))->format('d M Y') ?></td>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun article trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

            <footer class="app-footer">
                <p class="text-sm text-muted">2026 ClubEdge. Tous droits réservés.</p>
            </footer>
        </div>
    </div>

    <div class="mobile-overlay" id="mobile-overlay"></div>
    <script src="../../assets/js/main.js" defer></script>
</body>

</html>
