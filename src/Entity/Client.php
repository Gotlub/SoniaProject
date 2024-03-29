<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: RendezVous::class, cascade : ['detach'])]
    private Collection $rendezVous;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $adresse_client = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cp_client = null;

    #[ORM\OneToOne( )]
    #[ORM\JoinColumn(nullable: true)]
    private ?RendezVous $dernier_rdv = null;

    public function __construct()
    {
        $this->rendezVous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->nom . " " . $this->prenom;
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

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->rendezVous->contains($rendezVou)) {
            $this->rendezVous->add($rendezVou);
            $rendezVou->setClient($this);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        if ($this->rendezVous->removeElement($rendezVou)) {
            // set the owning side to null (unless already changed)
            if ($rendezVou->getClient() === $this) {
                $rendezVou->setClient(null);
            }
        }

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresseClient(): ?string
    {
        return $this->adresse_client;
    }

    public function setAdresseClient(?string $adresse_client): static
    {
        $this->adresse_client = $adresse_client;

        return $this;
    }

    public function getCpClient(): ?string
    {
        return $this->cp_client;
    }

    public function setCpClient(?string $cp_client): static
    {
        $this->cp_client = $cp_client;

        return $this;
    }

    public function getDernierRdv(): ?RendezVous
    {
        return $this->dernier_rdv;
    }

    public function setDernierRdv(?RendezVous $dernier_rdv): static
    {
        $this->dernier_rdv = $dernier_rdv;

        return $this;
    }

}
