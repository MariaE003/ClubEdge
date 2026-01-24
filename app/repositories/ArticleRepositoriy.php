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
        $stmt = $this->db->prepare("SELECT a.id, a.title, a.content, a.images, a.created_at
                                    FROM articles a
                                    JOIN clubs c ON a.club_id = c.id
                                    WHERE c.president_id = :president_id
                                    ORDER BY a.created_at DESC");
        $stmt->execute([':president_id' => $id]);
        $articlesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($articlesData as $data) {
            $article = new Article(
                (int)$data['id'],
                $data['title'],
                $data['content'],
                $data['images'],
                new DateTime($data['created_at'])
            );
            $articles[] = $article;
        }

        return $articles;
    }
}