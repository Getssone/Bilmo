<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $business = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $webSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $legalStatus = null;

    #[ORM\OneToMany(targetEntity: Particulier::class, mappedBy: 'client')]
    private Collection $ClientsParticulier;

    public function __construct()
    {
        $this->ClientsParticulier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getBusiness(): ?string
    {
        return $this->business;
    }

    public function setBusiness(string $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): static
    {
        $this->webSite = $webSite;

        return $this;
    }

    public function getLegalStatus(): ?string
    {
        return $this->legalStatus;
    }

    public function setLegalStatus(?string $legalStatus): static
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    /**
     * @return Collection<int, Particulier>
     */
    public function getClientsParticulier(): Collection
    {
        return $this->ClientsParticulier;
    }

    public function addClientsParticulier(Particulier $client): static
    {
        if (!$this->ClientsParticulier->contains($client)) {
            $this->ClientsParticulier->add($client);
            $client->setClient($this);
        }

        return $this;
    }

    public function removeClients(Particulier $client): static
    {
        if ($this->ClientsParticulier->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getClient() === $this) {
                $client->setClient(null);
            }
        }

        return $this;
    }
}
