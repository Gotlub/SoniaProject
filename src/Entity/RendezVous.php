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
    private ?string $status = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $facturation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_facturation = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $status_dossier = null;

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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type_RPQS = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'rendez_vous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?adresse $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?bool $EF_etudes = null;

    #[ORM\Column(nullable: true)]
    private ?bool $EDN = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
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

    public function getStatusDossier(): ?string
    {
        return $this->status_dossier;
    }

    public function setStatusDossier(?string $status_dossier): static
    {
        $this->status_dossier = $status_dossier;

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

    public function isEFEtudes(): ?bool
    {
        return $this->EF_etudes;
    }

    public function setEFEtudes(?bool $EF_etudes): static
    {
        $this->EF_etudes = $EF_etudes;

        return $this;
    }

    public function isEDN(): ?bool
    {
        return $this->EDN;
    }

    public function setEDN(?bool $EDN): static
    {
        $this->EDN = $EDN;

        return $this;
    }
}
