<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    private $updatedate;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("post:read")
     */
    private $entreprise;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->setUpdatedate(new DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }

    public function __construct()
    {
        $this->updateTimestamps();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUpdatedate(): ?DateTimeInterface
    {
        return $this->updatedate;
    }

    public function setUpdatedate(DateTimeInterface $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus()
    {
        $threeDaysAgo = new DateTime('-3 days');
        $aWeekAgo = new DateTime('-7 days');
        if ($this->updatedate < $aWeekAgo)
        {
            return 'danger';
        }
        if ($this->updatedate < $threeDaysAgo )
        {
            return 'warning';
        }
        return 'okay';
    }
}
