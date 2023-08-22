<?php

namespace App\Entity;

use App\Entity\Session;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StagiaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: StagiaireRepository::class)]
class Stagiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\ManyToMany(targetEntity: Session::class, inversedBy: 'stagiaires')]
    private Collection $session_stagiaire;

    public function __construct()
    {
        $this->session_stagiaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessionStagiaire(): Collection
    {
        return $this->session_stagiaire;
    }

    public function addSessionStagiaire(Session $sessionStagiaire): static
    {
        if (!$this->session_stagiaire->contains($sessionStagiaire)) {
            $this->session_stagiaire->add($sessionStagiaire);
        }

        return $this;
    }

    public function removeSessionStagiaire(Session $sessionStagiaire): static
    {
        $this->session_stagiaire->removeElement($sessionStagiaire);

        return $this;
    }

    public function __toString()
    {
        return $this->getNom() . ' ' . $this->getPrenom();
    }
}
