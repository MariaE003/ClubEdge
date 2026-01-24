<?php

class ArticleController 
{
    
    public function createArticle(){
        require_once __DIR__. "/../../app/views/president/create-article.php";
        }
        
    public function insertArticle(){
        require_once __DIR__. "/../repositories/ArticleRepository.php";
            

        $db = Database::getInstance()->getConnection();
        $articleRepo = new ArticleRepository($db);
        if (isset($_POST['submit'])) {
            
            $id_club = $_POST['idC'];   
            $event_id = $_POST['event_id'];
            $title = $_POST['article-title'];
            $content = $_POST['article-content'];
            $image = $_POST['cover_image_url'];
            $articleRepo->insertArticle($id_club, $event_id, $title, $content, $image);
            header("Location: /ClubEdge/president/ArticleManage");
            exit(); 
        }else{
            header("Location: /president/ArticleManage");
            exit(); 
        }

    }

    public function manageArticles(){
        require_once __DIR__. "/../views/president/articles-manage.php";
    }

    // pour affichier les acrticle
   public function manageArticlesByPresident(){
    require_once __DIR__ . "/../repositories/ArticleRepository.php"; 

    $db = Database::getInstance()->getConnection();
    $articleRepo = new ArticleRepository($db);

    $userId = $_SESSION['user_id'];

    $articles = $articleRepo->getArticlesByPresidentId($userId);

    return $articles;
    require_once __DIR__ . "/../views/president/articles-manage.php";
}

}