<?php 

class Club{
    private ?int $id;
    private string $name;
    private ?string $description;

    private ?int $presidentId;
    private ?string $logo;
    private ?string $createdAt; 
    private array $members;

    public function __construct(?int $id,string $name,?string $description=null,?int $presidentId=null,?string $logo = null,array $members=[],?string $createdAt = null){
    $this->id=$id;
    $this->setName($name);
    $this->description=$description;
    $this->logo=$logo;
    $this->presidentId=$presidentId;
    $this->setMembers($members);
    $this->createdAt=$createdAt;

    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name): void {
        if (trim($name)==='') {
            throw new Exception('le nom du club est obligatoire !');
        }
        $this->name = $name;
    }

    public function getDescription(): ?string {
        return $this->description;
    }
    public function setDescription(?string $description): void {
        $this->description = $description;
    }
    // 
    public function getPresidentId(): ?int{ 
        return $this->presidentId; 
    }
    public function setPresidentId(?int $presidentId){
        $this->presidentId = $presidentId;
    }

    public function getMembers(): array{ 
        return $this->members; 
    }

    public function setMembers(?array $members): void {
        $this->members = $members ?? []; // [] => pour eviter erreur en cas de loop && array vide
    }

    public function getLogo(): ?string{ 
        return $this->logo;  
    }
    public function setLogo(?string $logo): void {
        $this->logo = $logo;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt; 
    }



}

?>