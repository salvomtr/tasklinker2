<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\ManyToMany(targetEntity: Projet::class, inversedBy: 'employes')]
    private Collection $projets;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Tache::class)]
    private Collection $taches;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
        $this->taches = new ArrayCollection();
    }

    // Getters/Setters...

    public function getId(): ?int { return $this->id; }

    public function getPrenom(): ?string { return $this->prenom; }

    public function setPrenom(string $prenom): static {
        $this->prenom = $prenom;
        return $this;
    }

    public function getNom(): ?string { return $this->nom; }

    public function setNom(string $nom): static {
        $this->nom = $nom;
        return $this;
    }

    public function getEmail(): ?string { return $this->email; }

    public function setEmail(string $email): static {
        $this->email = $email;
        return $this;
    }

    public function getStatut(): ?string { return $this->statut; }

    public function setStatut(string $statut): static {
        $this->statut = $statut;
        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): static {
        $this->dateEntree = $dateEntree;
        return $this;
    }

    /** @return Collection<int, Projet> */
    public function getProjets(): Collection {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
        }
        return $this;
    }

    public function removeProjet(Projet $projet): static {
        $this->projets->removeElement($projet);
        return $this;
    }

    /** @return Collection<int, Tache> */
    public function getTaches(): Collection {
        return $this->taches;
    }

    public function addTache(Tache $tache): static {
        if (!$this->taches->contains($tache)) {
            $this->taches->add($tache);
            $tache->setEmploye($this);
        }
        return $this;
    }

    public function removeTache(Tache $tache): static {
        if ($this->taches->removeElement($tache)) {
            if ($tache->getEmploye() === $this) {
                $tache->setEmploye(null);
            }
        }
        return $this;
    }
}
