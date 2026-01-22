<?php

final class ArticleRepository{

    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function insertArticle($id_club, $event_id, $title, $content, $image): void{
        
        $stmt = $this->db->prepare("
            INSERT INTO articles (club_id, event_id, title, content, image, created_at)
            VALUES (:club_id, :event_id, :title, :content, :image, NOW())
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
}