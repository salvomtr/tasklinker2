<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Tache::class)]
    private Collection $taches;

    #[ORM\ManyToMany(targetEntity: Employe::class, inversedBy: 'projets')]
    private Collection $employes;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Statut::class, orphanRemoval: true)]
    private Collection $statuts;

    #[ORM\Column(type: 'boolean')]
    private ?bool $archive = false;


    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->employes = new ArrayCollection();
        $this->statuts = new ArrayCollection();
        $this->archive = false;
    }

    // Getters & setters pour id, nom, archive...

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }

    public function setNom(string $nom): static {
        $this->nom = $nom;
        return $this;
    }

    public function getTaches(): Collection { return $this->taches; }

    public function addTache(Tache $tache): static {
        if (!$this->taches->contains($tache)) {
            $this->taches->add($tache);
            $tache->setProjet($this);
        }
        return $this;
    }

    public function removeTache(Tache $tache): static {
        if ($this->taches->removeElement($tache)) {
            if ($tache->getProjet() === $this) {
                $tache->setProjet(null);
            }
        }
        return $this;
    }

    public function getEmployes(): Collection { return $this->employes; }

    public function addEmploye(Employe $employe): static {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
        }
        return $this;
    }

    public function removeEmploye(Employe $employe): static {
        $this->employes->removeElement($employe);
        return $this;
    }

    public function getStatuts(): Collection { return $this->statuts; }

    public function addStatut(Statut $statut): static {
        if (!$this->statuts->contains($statut)) {
            $this->statuts->add($statut);
            $statut->setProjet($this);
        }
        return $this;
    }

    public function removeStatut(Statut $statut): static {
        if ($this->statuts->removeElement($statut)) {
            if ($statut->getProjet() === $this) {
                $statut->setProjet(null);
            }
        }
        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): static
    {
        $this->archive = $archive;
        return $this;
    }

}
