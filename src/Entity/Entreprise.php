<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
class Entreprise
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
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="entreprise")
     */
    private $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
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

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * @return int
     */
    public function getNumberOfServices()
    {
        return $this->services->count();
    }

    /**
     * @return \DateTime
     */
    public function getDateLastReception()
    {
        $services = $this->services;
        $date = null;
        foreach($services as $service)
        {
            $updateDate = $service->getUpdatedate();
            if ($date == null || $date > $updateDate)
            {
                $date = $updateDate;
            }
        }
        return $date;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $status = 'okay';
        $services = $this->services;
        foreach($services as $service)
        {
            if($service->getStatus() == 'warning' && $status == 'okay')
            {
                $status = 'warning';
            }
            elseif ($service->getStatus() == 'danger')
            {
                $status = 'danger';
            }
        }
        return $status;

    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setEntreprise($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getEntreprise() === $this) {
                $service->setEntreprise(null);
            }
        }

        return $this;
    }
}
