<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual("today", message: "La date ne peut pas être dans le futur")]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 20, notInRangeMessage: "La note doit être entre 0 et 20")]
    private ?int $note = null;

    #[ORM\Column]
    private ?int $tempmin = null;

    #[ORM\Column]
    #[Assert\GreaterThan(propertyPath: "tempmin", message: "La t° max doit être supérieure à la t° min")]
    private ?int $tempmax = null;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    private ?Environnement $environnement = null;

    /**
     * CONSTRUCTEUR : Initialise les valeurs par défaut
     */
    public function __construct()
    {
        // "today" assure que l'heure est à 00:00:00, donc jamais dans le futur
        $this->datecreation = new \DateTime('today');
        
        $this->note = 0;
        
        // On met 0 et 1 pour respecter la règle "tempmax > tempmin"
        $this->tempmin = 0;
        $this->tempmax = 1;
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(string $ville): static { $this->ville = $ville; return $this; }

    public function getPays(): ?string { return $this->pays; }
    public function setPays(string $pays): static { $this->pays = $pays; return $this; }

    public function getDatecreation(): ?\DateTimeInterface { return $this->datecreation; }
    public function setDatecreation(\DateTimeInterface $datecreation): static { $this->datecreation = $datecreation; return $this; }

    public function getNote(): ?int { return $this->note; }
    public function setNote(int $note): static { $this->note = $note; return $this; }

    public function getTempmin(): ?int { return $this->tempmin; }
    public function setTempmin(int $tempmin): static { $this->tempmin = $tempmin; return $this; }

    public function getTempmax(): ?int { return $this->tempmax; }
    public function setTempmax(int $tempmax): static { $this->tempmax = $tempmax; return $this; }

    public function getEnvironnement(): ?Environnement
    {
        return $this->environnement;
    }

    public function setEnvironnement(?Environnement $environnement): static
    {
        $this->environnement = $environnement;
        return $this;
    }
}
