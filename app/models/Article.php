<?php

class Article{
    public int $id;
    public string $title;
    public string $content;
    public $image; 
    public DateTime $createdAt; 
    public function __construct(int $id, string $title, string $content, $image, DateTime $createdAt){
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->createdAt = $createdAt;
    }

    public function getId(): int{
        return $this->id;
    }
    public function getTitle(): string{
        return $this->title;
    }
    public function getContent(): string{
        return $this->content;  
    }
    public function getImage(){
        return $this->image;
    }
    public function getCreatedAt(): DateTime{
        return $this->createdAt;
    }
     
}