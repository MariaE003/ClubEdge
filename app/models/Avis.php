<?php

class Avis{
    private int $id;
    private string $comment;
    private int $rating;

    public function __construct(int $id, string $comment, int $rating){
        $this->id = $id;
        $this->comment = $comment;
        $this->rating = $rating;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getComment(): string{
        return $this->comment;
    }

    public function getRating(): int{
        return $this->rating;
    }
    
}