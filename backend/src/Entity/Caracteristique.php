<?php
// src/Entity/Caracteristique.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CaracteristiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: CaracteristiqueRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['caracteristique:read']], denormalizationContext: ['groups' => ['caracteristique:write']])]
class Caracteristique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['caracteristique:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['caracteristique:read', 'caracteristique:write', 'car:read'])]
    #[Assert\NotBlank] 
    private ?string $cle = null;

    #[ORM\Column(length: 255)]
    #[Groups(['caracteristique:read', 'caracteristique:write', 'car:read'])]
    #[Assert\NotBlank] 
    private ?string $value = null;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'caracteristiques', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['caracteristique:read', 'caracteristique:write'])]
    private ?Car $car = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(string $cle): static
    {
        $this->cle = $cle;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }
}