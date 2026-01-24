<?php

final class ArticleRepository{

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertArticle($id_club, $event_id, $title, $content, $image): void{
        
        $stmt = $this->db->prepare("
            INSERT INTO articles (club_id, event_id, title, content, images, created_at)
            VALUES (:club_id, :event_id, :title, :content, ARRAY[:image]::text[], NOW())
        ");

        $stmt->execute([
            ':club_id' => $id_club,
            ':event_id' => $event_id,
            ':title' => $title,
            ':content' => $content,
            ':image' => $image  
        ]);

        header("Location: /president/dashboard");
        exit(); 
    }

    public function getArticlesByPresidentId($id){
        $stmt = $this->db->prepare("SELECT a.title, e.title AS event_title, e.event_date
                                    FROM articles a  
                                    JOIN events e ON a.event_id = e.id 
                                    WHERE a.club_id = :president_id");
        $stmt->execute([':president_id' => $id]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $articles;
    }
}