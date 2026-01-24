<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// On ajoute cette ligne pour pouvoir utiliser les validations (étape 2)
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

    // --- NOUVEAUX CHAMPS POUR L'ETAPE 5 ---

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    // REGLE : La date doit être aujourd'hui ou dans le passé
    #[Assert\LessThanOrEqual("today", message: "La date ne peut pas être dans le futur")]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column]
    // REGLE : La note doit être entre 0 et 20
    #[Assert\Range(min: 0, max: 20, notInRangeMessage: "La note doit être entre 0 et 20")]
    private ?int $note = null;

    #[ORM\Column]
    private ?int $tempmin = null;

    #[ORM\Column]
    // REGLE : La température max doit être supérieure à la température min
    #[Assert\GreaterThan(propertyPath: "tempmin", message: "La t° max doit être supérieure à la t° min")]
    private ?int $tempmax = null;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    private ?environnement $environnement = null;

    // --- GETTERS ET SETTERS ---

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

    public function getEnvironnement(): ?environnement
    {
        return $this->environnement;
    }

    public function setEnvironnement(?environnement $environnement): static
    {
        $this->environnement = $environnement;

        return $this;
    }
}