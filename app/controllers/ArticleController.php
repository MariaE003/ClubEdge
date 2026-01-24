<?php

class ArticleController extends BaseController
{
    public function __construct(){
        parent::__construct();
        $this->requireRole1('admin');
    }
    
    public function createArticle(){
        require_once __DIR__. "/../../app/views/president/create-article.php";
        }
        
    public function insertArticle(){
        require_once __DIR__. "/../repositories/ArticleRepositoriy.php";
            

        $db = Database::getInstance()->getConnection();
        $articleRepo = new ArticleRepository($db);
        if (isset($_POST['submit'])) {
            
            $id_club = 1;   
            $event_id = $_POST['event_id'];
            $title = $_POST['article-title'];
            $content = $_POST['article-content'];
            $image = $_POST['cover_image_url'];
            $articleRepo->insertArticle($id_club, $event_id, $title, $content, $image);
        }else{
            header("Location: /president/CreateArticle");
            exit(); 
        }

    }

    public function manageArticles(){
        require_once __DIR__. "/../views/president/articles-manage.php";
    }
}