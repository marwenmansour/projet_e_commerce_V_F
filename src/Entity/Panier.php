<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_totale;

    /**
     * @ORM\ManyToMany(targetEntity=FruitsLegumes::class)
     */
    private $fruitETlegumes;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="panier")
     */
    private $user;

    public function __construct()
    {
        $this->fruitETlegumes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixTotale(): ?float
    {
        return $this->prix_totale;
    }

    public function setPrixTotale(float $prix_totale): self
    {
        $this->prix_totale = $prix_totale;

        return $this;
    }

    /**
     * @return Collection|FruitsLegumes[]
     */
    public function getFruitETlegumes(): Collection
    {
        return $this->fruitETlegumes;
    }

    public function addFruitETlegume(FruitsLegumes $fruitETlegume): self
    {
        if (!$this->fruitETlegumes->contains($fruitETlegume)) {
            $this->fruitETlegumes[] = $fruitETlegume;
        }

        return $this;
    }

    public function removeFruitETlegume(FruitsLegumes $fruitETlegume): self
    {
        $this->fruitETlegumes->removeElement($fruitETlegume);

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }
}
