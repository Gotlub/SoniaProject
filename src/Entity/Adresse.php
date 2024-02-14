<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $prochaine_visite = null;

    #[ORM\Column(name: "adresse", length: 2000)]
    private ?string $adresseVisite = null;

    #[ORM\Column(length: 255)]
    private ?string $cp = null;

    #[ORM\Column(length: 1000)]
    private ?string $commune = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $section_cadastrale = null;

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $ancienne_adresse = null;

    #[ORM\OneToMany(mappedBy: 'Adresse', targetEntity: RendezVous::class)]
    private Collection $rendez_vous;

    public function __construct()
    {
        $this->rendez_vous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProchaineVisite(): ?\DateTimeInterface
    {
        return $this->prochaine_visite;
    }

    public function setProchaineVisite(?\DateTimeInterface $prochaine_visite): static
    {
        $this->prochaine_visite = $prochaine_visite;

        return $this;
    }

    public function getAdresseVisite(): ?string
    {
        return $this->adresseVisite;
    }

    public function setAdresseVisite(string $adresseVisite): static
    {
        $this->adresseVisite = $adresseVisite;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): static
    {
        $this->commune = $commune;

        return $this;
    }

    public function getSectionCadastrale(): ?string
    {
        return $this->section_cadastrale;
    }

    public function setSectionCadastrale(?string $section_cadastrale): static
    {
        $this->section_cadastrale = $section_cadastrale;

        return $this;
    }

    public function getAncienneAdresse(): ?string
    {
        return $this->ancienne_adresse;
    }

    public function setAncienneAdresse(?string $ancienne_adresse): static
    {
        $this->ancienne_adresse = $ancienne_adresse;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->rendez_vous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->rendez_vous->contains($rendezVou)) {
            $this->rendez_vous->add($rendezVou);
            $rendezVou->setAdresse($this);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        if ($this->rendez_vous->removeElement($rendezVou)) {
            // set the owning side to null (unless already changed)
            if ($rendezVou->getAdresse() === $this) {
                $rendezVou->setAdresse(null);
            }
        }

        return $this;
    }
}
