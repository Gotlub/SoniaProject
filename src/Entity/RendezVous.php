<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $facturation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_facturation = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $type_controle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $num_dossier = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_controle = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $type_traitement = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $type_installation = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $rejet_inf = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $conformite = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $impact = null;

    #[ORM\Column(length: 255, nullable : true)]
    private ?string $type_RPQS = null;

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $adresse_facturation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cp_facturation = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $commune_facturation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_propriaitaire = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $prenom_propriaitaire = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous', cascade : ['detach'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'rendez_vous', cascade : ['detach'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Adresse $adresse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacturation(): ?string
    {
        return $this->facturation;
    }

    public function setFacturation(?string $facturation): static
    {
        $this->facturation = $facturation;

        return $this;
    }

    public function getDateFacturation(): ?string
    {
        return $this->date_facturation;
    }

    public function setDateFacturation(?string $date_facturation): static
    {
        $this->date_facturation = $date_facturation;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getTypeControle(): ?string
    {
        return $this->type_controle;
    }

    public function setTypeControle(?string $type_controle): static
    {
        $this->type_controle = $type_controle;

        return $this;
    }

    public function getNumDossier(): ?string
    {
        return $this->num_dossier;
    }

    public function setNumDossier(?string $num_dossier): static
    {
        $this->num_dossier = $num_dossier;

        return $this;
    }

    public function getDateControle(): ?\DateTimeInterface
    {
        return $this->date_controle;
    }

    public function setDateControle(?\DateTimeInterface $date_controle): static
    {
        $this->date_controle = $date_controle;

        return $this;
    }

    public function getTypeTraitement(): ?string
    {
        return $this->type_traitement;
    }

    public function setTypeTraitement(?string $type_traitement): static
    {
        $this->type_traitement = $type_traitement;

        return $this;
    }

    public function getTypeInstallation(): ?string
    {
        return $this->type_installation;
    }

    public function setTypeInstallation(?string $type_installation): static
    {
        $this->type_installation = $type_installation;

        return $this;
    }

    public function getRejetInf(): ?string
    {
        return $this->rejet_inf;
    }

    public function setRejetInf(?string $rejet_inf): static
    {
        $this->rejet_inf = $rejet_inf;

        return $this;
    }

    public function getConformite(): ?string
    {
        return $this->conformite;
    }

    public function setConformite(?string $conformite): static
    {
        $this->conformite = $conformite;

        return $this;
    }

    public function getImpact(): ?string
    {
        return $this->impact;
    }

    public function setImpact(?string $impact): static
    {
        $this->impact = $impact;

        return $this;
    }

    public function getTypeRPQS(): ?string
    {
        return $this->type_RPQS;
    }

    public function setTypeRPQS(?string $type_RPQS): static
    {
        $this->type_RPQS = $type_RPQS;

        return $this;
    }

    public function getAdresseFacturation(): ?string
    {
        return $this->adresse_facturation;
    }

    public function setAdresseFacturation(?string $adresse_facturation): static
    {
        $this->adresse_facturation = $adresse_facturation;

        return $this;
    }

    public function getCpFacturation(): ?string
    {
        return $this->cp_facturation;
    }

    public function setCpFacturation(?string $cp_facturation): static
    {
        $this->cp_facturation = $cp_facturation;

        return $this;
    }

    public function getCommuneFacturation(): ?string
    {
        return $this->commune_facturation;
    }

    public function setCommuneFacturation(?string $commune_facturation): static
    {
        $this->commune_facturation = $commune_facturation;

        return $this;
    }

    public function getNomPropriaitaire(): ?string
    {
        return $this->nom_propriaitaire;
    }

    public function setNomPropriaitaire(?string $nom_propriaitaire): static
    {
        $this->nom_propriaitaire = $nom_propriaitaire;

        return $this;
    }

    public function getPrenomPropriaitaire(): ?string
    {
        return $this->prenom_propriaitaire;
    }

    public function setPrenomPropriaitairee(?string $prenom_propriaitaire): static
    {
        $this->prenom_propriaitaire = $prenom_propriaitaire;

        return $this;
    }

    /**
     * Get the value of client
     *
     * @return ?Client
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @param ?Client $client
     *
     * @return self
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAdresse(): ?adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }
}
