<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire au moins {{ limit }} caractères", maxMessage: "Le nom ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le nom ne doit contenir que des lettres et des espaces")]
    private string $name;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(["getUsers"])]
    #[Assert\Length(min: 0, max: 14, allowEmptyString: true, minMessage: "Le SIRET doit faire au moins {{ limit }} caractères", maxMessage: "Le SIRET ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers"])]
    #[Assert\NotBlank(message: "L'activité est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "L'activité doit faire au moins {{ limit }} caractères", maxMessage: "L'activité ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9\s]+$/", message: "L'activité ne doit contenir que des lettres, des chiffres et des espaces")]
    private string $business;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUsers"])]
    #[Assert\Length(min: 0, max: 255, allowEmptyString: true, minMessage: "L'URL du site web doit faire au moins {{ limit }} caractères", maxMessage: "L'URL du site web ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Url(message: "L'URL du site web n'est pas valide")]
    private ?string $webSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getUsers"])]
    #[Assert\Length(min: 0, max: 255, allowEmptyString: true, minMessage: "Le statut juridique doit faire au moins {{ limit }} caractères", maxMessage: "Le statut juridique ne peut pas faire plus de {{ limit }} caractères")]
    #[Assert\Regex(pattern: "/^[a-zA-Z\s]+$/", message: "Le statut juridique ne doit contenir que des lettres et des espaces")]
    private ?string $legalStatus = null;

    #[ORM\OneToMany(targetEntity: Particulier::class, mappedBy: 'client')]
    #[Assert\Valid]
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
