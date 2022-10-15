<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandRepository::class)]
class Demand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = '';

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = '';
    
    
    #[ORM\Column]
    private ?int $deleted = 0;
    
    #[ORM\Column]
    private ?string $date_created = null ;
    
    #[ORM\Column]
    private ?string $date_modified = null ;

    #[ORM\Column]
    private ?string $photo = null ;
    
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
    
    public function setDeleted(int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
    
    public function getDeleted(): ?int
    {
        return $this->deleted;
    }

    
    public function getDate_created()
    {
        return $this->date_created ;
    }
    
    public function setDate_created($date): self
    {
        $this->date_created = $date;

        return $this;
    }

    public function getDate_modified()
    {
        return $this->date_modified ;
    }

    public function setDate_modified($date): self
    {
        $this->date_modified = $date;
        return $this;
    }
    
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
