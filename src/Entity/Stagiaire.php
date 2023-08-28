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
    private Collection $stagiaire_session;

    public function __construct()
    {
        $this->stagiaire_session = new ArrayCollection();
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
        return $this->stagiaire_session;
    }

    public function addSessionStagiaire(Session $sessionStagiaire): static
    {
        if (!$this->stagiaire_session->contains($sessionStagiaire)) {
            $this->stagiaire_session->add($sessionStagiaire);
        }

        return $this;
    }

    public function removeSessionStagiaire(Session $sessionStagiaire): static
    {
        $this->stagiaire_session->removeElement($sessionStagiaire);

        return $this;
    }

    public function __toString()
    {
        return $this->getNom() . ' ' . $this->getPrenom();
    }
}
