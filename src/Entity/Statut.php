<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'statuts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Tache::class)]
    private Collection $taches;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }

    public function setNom(string $nom): static {
        $this->nom = $nom;
        return $this;
    }

    public function getProjet(): ?Projet { return $this->projet; }

    public function setProjet(?Projet $projet): static {
        $this->projet = $projet;
        return $this;
    }

    public function getTaches(): Collection { return $this->taches; }

    public function addTache(Tache $tache): static {
        if (!$this->taches->contains($tache)) {
            $this->taches->add($tache);
            $tache->setStatut($this);
        }
        return $this;
    }

    public function removeTache(Tache $tache): static {
        if ($this->taches->removeElement($tache)) {
            if ($tache->getStatut() === $this) {
                $tache->setStatut(null);
            }
        }
        return $this;
    }
}
