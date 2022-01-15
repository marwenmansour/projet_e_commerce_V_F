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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="bigint")
     */
    private $telephone;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
