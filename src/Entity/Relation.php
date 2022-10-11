<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_demand = null;

    #[ORM\Column]
    private ?int $id_user = null;
    
    
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDemand(): ?int
    {
        return $this->id_demand;
    }

    public function setIdDemand(int $id_demand): self
    {
        $this->id_demand = $id_demand;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }
    
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    
}
