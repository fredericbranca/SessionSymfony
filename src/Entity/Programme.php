<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nb_jour = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?module $module = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?session $session = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJour(): ?int
    {
        return $this->nb_jour;
    }

    public function setNbJour(int $nb_jour): static
    {
        $this->nb_jour = $nb_jour;

        return $this;
    }

    public function getModule(): ?module
    {
        return $this->module;
    }

    public function setModule(?module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getSession(): ?session
    {
        return $this->session;
    }

    public function setSession(?session $session): static
    {
        $this->session = $session;

        return $this;
    }
}
