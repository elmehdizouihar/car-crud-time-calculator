<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ApiResource
 * @ORM\Table(name="car", uniqueConstraints={@ORM\UniqueConstraint(name="unique_model", columns={"model"})})
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;


    /**
     * @ORM\Column(type="float")
     */
    private $kmh; 

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\OneToMany(targetEntity=Caracteristique::class, mappedBy="car", cascade={"persist"})
     */
    private $caracteristiques;

    public function __construct()
    {
        $this->caracteristiques = new ArrayCollection();
    }

    /**
     * @return Collection|Caracteristique[]
     */
    public function getCaracteristiques(): Collection
    {
        return $this->caracteristiques;
    }

    // ajouter une caractéristique à la voiture
    public function addCaracteristique(Caracteristique $caracteristique): self
    {
        if (!$this->caracteristiques->contains($caracteristique)) {
            $this->caracteristiques[] = $caracteristique;
            $caracteristique->setCar($this); 
        }

        return $this;
    }

    public function removeCaracteristique(Caracteristique $caracteristique): self
    {
        if ($this->caracteristiques->removeElement($caracteristique)) {
            if ($caracteristique->getCar() === $this) {
                $caracteristique->setCar(null);
            }
        }

        return $this;
    }


    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }



    public function getKmh(): ?float
    {
        return $this->kmh;
    }

    public function setKmh(float $kmh): self
    {
        $this->kmh = $kmh;

        return $this;
    }

}
