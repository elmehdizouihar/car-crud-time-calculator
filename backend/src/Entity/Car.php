<?php
// src/Entity/Car.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Config\Api\CarApiConfig;
use App\Controller\CalculateTravelTimeAction;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(order: ['id' => 'DESC']),
        new Post(),
        new Put(),
        new Delete(),
        new Post(
            uriTemplate: '/cars/calculate_travel_time',
            controller: CalculateTravelTimeAction::class,
            defaults: CarApiConfig::CALCULATE_TRAVEL_TIME_DEFAULTS,
            name: 'calculate_travel_time',
        ),
    ],
    normalizationContext: ['groups' => ['car:read']],
    denormalizationContext: ['groups' => ['car:write']]
)]
#[UniqueEntity(fields: ['model'], message: "Ce modèle de voiture existe déjà.")]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:read'])]
    private int $id;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['car:read', 'car:write'])]
    #[Assert\NotBlank(message: "Le modèle de la voiture est obligatoire.")]
    private $model;

    #[ORM\Column(type: 'float', nullable: true)] // nullable: true permet à la validation Symfony de retourner le message d'erreur personnalisé de Assert\NotNull au lieu de générer une erreur de type.
    #[Groups(['car:read', 'car:write'])]
    #[Assert\NotNull(message: "La vitesse (km/h) ne peut pas être vide.")]
    #[Assert\Type(type: 'float', message: "La vitesse maximale doit être un nombre décimal.")]
    #[Assert\Positive(message: "La vitesse maximale doit être supérieure à 0.")]
    private ?float $kmh = null; // ?float permet à la propriété d'être nullable en PHP, mais Assert\NotNull garantit qu'elle ne peut pas être nulle, affichant ainsi le message d'erreur personnalisé au lieu de générer une erreur de type.

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Caracteristique::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['car:read', 'car:write'])]
    #[ApiProperty(writableLink: true)]
    private Collection $caracteristiques;

    public function __construct()
    {
        $this->caracteristiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getKmh(): ?float
    {
        return $this->kmh;
    }

    public function setKmh(?float $kmh): static
    {
        $this->kmh = $kmh;

        return $this;
    }

    public function getCaracteristiques(): Collection
    {
        return $this->caracteristiques;
    }

    public function addCaracteristique(Caracteristique $caracteristique): static
    {
        if (!$this->caracteristiques->contains($caracteristique)) {
            $this->caracteristiques->add($caracteristique);
            $caracteristique->setCar($this);
        }

        return $this;
    }

    public function removeCaracteristique(Caracteristique $caracteristique): static
    {
        if ($this->caracteristiques->removeElement($caracteristique)) {
            if ($caracteristique->getCar() === $this) {
                $caracteristique->setCar(null);
            }
        }

        return $this;
    }
    
    public function setCaracteristiques(array $caracteristiques): static
    {
        // Supprimez les caractéristiques qui ne sont plus dans la liste
        foreach ($this->caracteristiques as $existingCaracteristique) {
            if (!in_array($existingCaracteristique, $caracteristiques, true)) {
                $this->removeCaracteristique($existingCaracteristique);
            }
        }

        // Ajoutez ou mettez à jour les caractéristiques
        foreach ($caracteristiques as $caracteristique) {
            if (!$this->caracteristiques->contains($caracteristique)) {
                $this->addCaracteristique($caracteristique);
            }
        }

        return $this;
    }
    
}